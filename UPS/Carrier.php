<?php

/**
 * Classes for the UPS shipping services.
 * 
 * PHP versions 5.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @copyright  2008 Santiance Corporation
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @version    CVS: $Id:$
 * @link       http://www.santiance.com/open/Services_Shipping
 */

require_once 'Services/Shipping/XML/Carrier.php';

/**
 * An XML based carrier that deals with UPS (TM).
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/UPS
 */
class Services_Shipping_Driver_UPS
    extends Services_Shipping_XML_Carrier
{
    /* Service Identifiers */
    
    /**
     * UPS Next Day Air service
     */
    const NEXT_DAY_AIR = '01';
    
    /**
     * UPS Second Day Air service
     */
    const SECOND_DAY_AIR = '02';
    
    /**
     * UPS Ground service
     */
    const GROUND = '03';
    
    /**
     * UPS 3-Day Select service
     */
    const THREE_DAY_SELECT = '12';
    
    /**
     * UPS Next Day Air service
     */
    const NEXT_DAY_AIR_SAVER = '13';
    
    /**
     * UPS Next Day Air (Early AM) service
     */
    const NEXT_DAY_AIR_EARLY_AM = '14';
    
    /**
     * UPS 2nd Day Air (Early AM) service
     */
    const SECOND_DAY_AIR_AM = '59';
    
    /**
     * UPS Saver service
     */
    const UPS_SAVER = '65';
    
    /**
     * All the UPS services keyed by their identifier with a
     * human readable name.
     *
     * @var array
     */
    public static $service_names = array(
        self::NEXT_DAY_AIR => 'UPS Next Day Air',
        self::SECOND_DAY_AIR => 'UPS Second Day Air',
        self::GROUND => 'UPS Ground',
        self::THREE_DAY_SELECT => 'UPS 3-Day Select',
        self::NEXT_DAY_AIR_SAVER => 'UPS Next Day Air Saver',
        self::NEXT_DAY_AIR_EARLY_AM => 'UPS Next Day Air (Early AM)',
        self::SECOND_DAY_AIR_AM => 'UPS Second Day Air (AM)',
        self::UPS_SAVER => 'UPS Saver'
    );
    
    /* Environments */
    /**
     * URLS for the sandbox development environment. Set the 'environment'
     * option to this for the sandbox.
     * 
     * @var array
     */
    public static $ENV_DEVELOPMENT = array
    (
        'rate'        => 'https://wwwcie.ups.com/ups.app/xml/Rate',
        'track'       => 'https://wwwcie.ups.com/ups.app/xml/Track',
        'verify'      => 'https://wwwcie.ups.com/ups.app/xml/AV',
        'shipConfirm' => 'https://wwwcie.ups.com/ups.app/xml/ShipConfirm',
        'shipAccept'  => 'https://wwwcie.ups.com/ups.app/xml/ShipAccept'
    );
    
    /**
     * URLs for the production environment. If you use these you are
     * responsible for the packages produced. Use $ENV_DEVELOPMENT for
     * the sandbox or testing.
     * 
     * @var array
     */
    public static $ENV_PRODUCTION = array
    (
        'rate'         => 'https://www.ups.com/ups.app/xml/Rate',
        'track'        => 'https://www.ups.com/ups.app/xml/Track',
        'verify'       => 'https://www.ups.com/ups.app/xml/AV',
        'shipConfirm'  => 'https://www.ups.com/ups.app/xml/ShipConfirm',
        'shipAccept'   => 'https://www.ups.com/ups.app/xml/ShipAccept'
    );
    
    /**
     * An array of URLs for different HTTP POST operations. This is used
     * to switch between sandbox and production.
     * 
     * @var array
     */
    protected $environment;
    
    /**
     * An AccessRequest XML node. This appears in every XML transaction.
     * 
     * @var DOMNode
     */
    protected $accessRequest;
    
    /**
     * UPS account number ("Shipper Number"). Should be 6 characters.
     * 
     * @var string
     */
    protected $accountId;
    
    /**
     * Parcel handlers for UPS features for parcels.
     *
     * @var array
     */
    protected $parcelFeatureHandlers = array();
    
    /**
     * Feature handlers for UPS features for shipments.
     *
     * @var array
     */
    protected $shipmentFeatureHandlers = array();
    
    public static function hasService($id)
    {
    	return array_key_exists($id, self::$service_names);
    }
    
    /**
     * Construct a UPS carrier. You must set the 'username','password',
     * and 'accessKey' options to your UPS account credentials. If you wish
     * to make shipment on behalf of your account you'll need to set 'accountId'
     * to your
     * account number, otherwise you may only use 3rd party billing.
     *
     * @param array $options specified options (see method description)
     * described in the class description.
     */
    public function __construct($options = null)
    {
        parent::__construct($options);
        
        // Register parcel feature handlers
        $this->registerParcelFeature(new Services_Shipping_Driver_UPS_InsuredValue());
        $this->registerParcelFeature(new Services_Shipping_Driver_UPS_DeliveryConfirmation());
        $this->registerParcelFeature(new Services_Shipping_Driver_UPS_AdditionalHandling());
        
        // Register shipment feature handler
        $this->registerShipmentFeature(new Services_Shipping_Driver_UPS_SaturdayDelivery());
        
        if (empty($options['environment'])) {
            $this->environment = self::$ENV_DEVELOPMENT;
        } else {
            $this->environment = $options['environment'];
        }
        
        //if (!($options['username'] && $options['password'])) {
        //    throw new Exception("'username' and 'password' options are required");
        //}
        
        Services_Shipping_XML::struct('AccessRequest', array(
            '@xml:lang'           => 'en-US',
            'AccessLicenseNumber' => $options['accessKey'],
            'UserId'              => $options['username'],
            'Password'            => $options['password']
        ), $this->accessRequest);
        
        $this->accountId = $options['accountId'];
    }
    
     /**
      * Removes a handler for parcel features. If the handler doesn't exist nothing
      * happens.
      *
      * @param Services_Shipping_ParcelFeature $feature the handler to remove
      * 
      * @return void
      */
    protected function registerParcelFeature(Services_Shipping_ParcelFeature $feature)
    {
        $key = $feature->getKey();
        $value =& $this->parcelFeatureHandlers[$key];
    
        if (!empty($value)) {
            return;
        }
    
        $value = $feature;
    }
    
    /**
     * Removes a handler for shipment features. If the handler doesn't exist nothing
     * happens.
     *
     * @param Services_Shipping_ShipmentFeature $feature the handler to remove
     * 
     * @return void
     */
    protected function registerShipmentFeature(Services_Shipping_ShipmentFeature $feature)
    {
        $key = $feature->getKey();
        $value =& $this->shipmentFeatureHandlers[$key];
    
        if (!empty($value)) {
            return;
        }
    
        $value = $feature;
    }
    
    /**
     * Gets all the services this UPS carrier provides. This array
     * is keyed by the service identifier, which you should use as
     * for 'service' when using {@link #createParcel()}. The values
     * are the human readable service names.
     *
     * @return array the set of services
     */
    public function getServices()
    {
        return self::$service_names;
    }
    
    /**
     * Validates an address for UPS shipping services. This uses
     * the Address Validation System (AVS). This is not a credit
     * card Adress Verification System, see the Payment_Process
     * PEAR package.
     *
     * @param Service_Shipping_Address $address the address to validate
     * 
     * @return array contains a set of Services_Shipping_Driver_UPS_VerifiedAddress
     *         objects that describe the different possible valid addresses.
     */
    public function validate(Service_Shipping_Address $address)
    {
        $request = Services_Shipping_XML::struct('AddressValidationRequest', array(
            'Request' => array(
                'RequestAction' => 'AV',
                //'RequestOption' => '',
                'TransactionReference' => array(
                    // context info must be < 512 chars
                    'CustomerContext' => 'I like money',
                    'XpciVersion' => '1.0'
                   )
              ),
              
            'Address' => array(
                'PostalCode' => $address->getPostalCode(),
                'City' => $address->getCity(),
                'StateProvidenceCode' => $address->getState()
             )
        ));
        
        if (Services_Shipping::$debugging) {
            $request->formatOutput = true;
        }
        
        $response = $this->request($this->environment['verify'], $request);
        $xpath    = new DOMXPath($response);
    
        if (Services_Shipping::$debugging) {
            $response->formatOutput = true;
                 
            if (Services_Shipping::$debugging > 2) {
                echo $response->saveXML();
            }
        }
        
        $results = array();
       
        foreach ($xpath->query('//*/AddressValidationResult') as $node) {
            $results[] = new Services_Shipping_Driver_UPS_VerifiedAddress($node);
        }
        
        return $results;
    }
    
    /**
     * Transforms the DOMDocument into a string. For UPS
     * this concatenates the AccessRequest node and the
     * request document.
     *
     * @param DOMDocument $node the node to prepare
     * 
     * @return string the node as a string
     */
    protected function prepareRequest(DOMDocument $node)
    {
        return $this->accessRequest->saveXML() . $node->saveXML();
    }

    /**
     * Makes an XML request to one of the UPS target URL.
     * libcurl and SSL are handled by the
     * parent class.
     * 
     * This checks for incoming errors, warnings, and exceptions.
     * 
     * If Services_Shipping::$debugging is enabled output formatting
     * of the XML documents will be enabled. In a production environment
     * you may disable this to reduce bandwidth.
     *
     * @param string      $target the UPS service target
     * @param DOMDocument $node   the request node
     * 
     * @return DOMDocument|null the response document
     */
    protected function request($target, DOMDocument $node)
    {
        $response = parent::request($target, $node);
        
        if (Services_Shipping::$debugging > 0) {
            $response->formatOutput = true;
        }
        
        $xpath = new DOMXPath($response);
        $path = '//*/Response';
        
        if (1 != $xpath->evaluate("number($path/ResponseStatusCode/text())", $response)) {
            if (Services_Shipping::$debugging > 2) {
                echo $node->saveXML();
            }
            
            foreach ($xpath->query("$path/Error", $response) as $errorNode) {
                $desc = $xpath->evaluate("string(ErrorDescription)", $errorNode);
                $code = $xpath->evaluate("string(ErrorCode)", $errorNode);
                
                if (Services_Shipping::$debugging > 2) {
                	echo $response->saveXML();
           		}
                
                throw new Services_Shipping_Exception("UPS Error ($code): $desc");
            }
        }
        
        return $response;
    }
    
    /**
     * Converts a ShippingAddress into an XML structure. The street address
     * is split by line and filled into AddressLine1, AddressLine2, and
     * AddressLine3.
     *
     * @param ShippingAddress $address the address to convert
     * 
     * @return array the XML structure
     * 
     * @throws ShippingException if the address is invalid. This includes
     * having too many street address lines.
     */
    protected function addressToXML($address)
    {
        if (!$address || !($address instanceof Services_Shipping_Address)) {
            throw new Services_Shipping_Exception("Address must be specified");
        }
    
        $struct = array(
            'City' => $address->getCity(),
            'StateProvinceCode' => $address->getState(),
            'PostalCode' => $address->getPostalCode(),
            'PostcodeExtendedLow' => array(),
            'CountryCode' => $address->getCountry()
        );
        
        $addressLines = explode("\n", $address->getStreet());
        
        if (!$addressLines || count($addressLines) > 3) {
            throw new Services_Shipping_Exception("UPS street address lines must be between 1-3");
        }
        
        foreach ($addressLines as $key => $value) {
            $struct["AddressLine".($key+1)] = $value;
        }
    
        return $struct;
    }
    
    /**
     * Takes a parcel and gets a UPS type code for it.
     *
     * @param Services_Shipping_Parcel $parcel the parcel
     * 
     * @return string the UPS type code
     */
    protected function getParcelTypeCode(Services_Shipping_Parcel $parcel)
    {
        if ($parcel instanceof Services_Shipping_Package) {
            return Services_Shipping_Driver_UPS_Parcel::PACKAGE;
        } else if ($parcel instanceof Services_Shipping_Letter) {
            return Services_Shipping_Driver_UPS_Parcel::LETTER;
        }
        
        throw new Services_Shipping_Exception("Unknown parcel type: ".gettype($parcel));
    }
    
    /**
     * Transforms a Shipment into an array of data.
     *
     * @param Services_Shipping_Shipment $shipment Shipment the shipment to transform
     * 
     * @return array the shipment data
     */
    protected function shipmentData(Services_Shipping_Shipment $shipment)
    {
        return array(
            'Description' => $shipment->getDescription(),
            'Shipper' => array(
                'Name' => $shipment->getSender()->getName(),
                'PhoneNumber' => $shipment->getSender()->getPhoneNumber(),
                'ShipperNumber' => $this->accountId,
                'TaxIdentificationNumber' => '1234567877',
                'Address' => $this->addressToXML($shipment->getSender()->getAddress())
            ),
            
            'ShipTo' => array(
                'CompanyName' => $shipment->getRecipient()->getName(),
                'AttentionName' => $shipment->getRecipient()->getName(),
                'PhoneNumber' => $shipment->getRecipient()->getPhoneNumber(),
                'Address' => $this->addressToXML($shipment->getRecipient()->getAddress())
            ),
            
            'ShipFrom' => array(
                'CompanyName' => $shipment->getSender()->getName(),
                'AttentionName' => $shipment->getSender()->getName(),
                'PhoneNumber' => $shipment->getSender()->getPhoneNumber(),
                //'TaxIdentificationNumber' => '123456789',
               'Address' => $this->addressToXML($shipment->getSender()->getAddress())
            )
        );
    }
    
    /**
     * Gets a Shipment as an XML node.
     *
     * @param Services_Shipping_Shipment $shipment the shipment to get data of
     * @param DOMDocument                $document the XML document to use
     * @param mixed                      $data     if set uses this data, otherwise uses shipmentData
     * 
     * @return array the shipment data as an array
     */
    protected function shipmentXML(Services_Shipping_Shipment $shipment, DOMDocument $document, $data = null)
    {
        if ($data == null) {
            $data = $this->shipmentData($shipment);
        }
        
        $data['ShipmentServiceOptions'] = array();

        $features = $shipment->getFeatures();
        
        if (isset($features)) {
            foreach ($features as $feature => $option) {
                $handler = $this->shipmentFeatureHandlers[$feature];
                   
                if (empty($handler)) {
                    trigger_error("Unknown shipment feature $feature", E_USER_WARNING);
                    continue;
                }
                
                $handler->apply($option, $data);
            }
        }
        
        $shipmentNode = Services_Shipping_XML::struct('Shipment', $data, $document, false);
        
        foreach ($shipment->getContents() as $parcel) {
            $parcelData = array(
                'PackageServiceOptions' => array(),
                'PackagingType' => array(
                    'Code' => $this->getParcelTypeCode($parcel),
                    'Description' => $parcel->getDescription()
                 ),
                 'Description' => $parcel->getDescription()
            );
            
            if ($parcel instanceof Services_Shipping_Package) {
                $parcelData['PackageWeight'] = array(
                    'Weight' => $parcel->getWeight(),
                    'UnitOfMeasurement' => array('Code' => 'LBS')
                );
            }
            
            $features = $parcel->getFeatures();
            
            if (isset($features)) {
                foreach ($features as $feature => $option) {
                    $handler =& $this->parcelFeatureHandlers[$feature];
                    
                    if (empty($handler)) {
                        trigger_error("Unknown parcel feature $feature", E_USER_WARNING);
                        continue;
                    }
                    
                    $handler->apply($option, $parcelData);
                }
            }
            
            $packageNode = Services_Shipping_XML::struct('Package', $parcelData, $document, false);
            $shipmentNode->appendChild($packageNode);
        }
        
        return $shipmentNode;
    }
    
    /**
     * Makes a shipment with UPS. If you're in the production enironment this
     * shipment is live and you will be charged if you don't VOID it.
     *
     * @param Services_Shipping_Shipment $shipment the shipment
     * 
     * @return ServiceS_Shipping_ShipmentReceipt the transaction receipt
     */
    public function ship(Services_Shipping_Shipment $shipment)
    {
    	//if (!in_array($shipment->getService(), Services_Shipping_Driver_UPS::$services)) {
    	
    	if (!array_key_exists($shipment->getService(), self::$service_names)) {
            $msg = "Unknown UPS service: {$shipment->getService()}";
            throw new InvalidArgumentException($msg);
        }
        
        $confirmRequestNode = Services_Shipping_XML::struct('ShipmentConfirmRequest', array(
            '@xml:lang' => 'en-US',
            'Request' => array(
                'TransactionReference' => array(
                    'CustomerContext' => 'none',
                    'XpciVersion' => '1.0001'
                 ),
                 'RequestAction' => 'ShipConfirm',
                 'RequestOption' => 'nonvalidate'
            ),
            
            'LabelSpecification' => array(
                'LabelPrintMethod' => array(
                    'Code' => 'GIF'
                    //'Description' => 'gif file'
                 ),
                
                //'HTTPUserAgent' => 'Mozilla/4.5', // TODO we arent a browser
                'LabelImageFormat' => array(
                   'Code' => 'GIF'
                   //'Description' => 'gif'
                )
            )
        ), $confirmRequest);
        
        $shipmentData = $this->shipmentData($shipment);
        
        $shipper =& $shipmentData['Shipper'];
        $shipper['TaxIdentificationNumber'] = '123456789';
        $shipper['ShipperNumber'] = $this->accountId;
        
        $shipTo =& $shipmentData['ShipTo'];
        //$shipTo['TaxIdentificationNumber'] = '123456789';
        
        $shipmentData['PaymentInformation'] = array(
            'Prepaid' => array(
                'BillShipper' => array(
                    'AccountNumber' => $this->accountId
                )
            )
        );
        
        $shipmentData['Service'] = array('Code' => $shipment->getService());
        
        $shipmentNode = $this->shipmentXML($shipment, $confirmRequest, $shipmentData);
        //unset($shipmentData);
        
        $confirmRequestNode->appendChild($shipmentNode);
        $confirmResponse = $this->request($this->environment['shipConfirm'], $confirmRequest);
        
        $xpath = new DOMXPath($confirmResponse);
        $nodes = $xpath->query('//ShipmentConfirmResponse/ShipmentCharges');
        
        if ($nodes === false || $nodes->length <= 0) {
            throw new LogicException("Expected ShipmentCharges from XML response");
        }
        
        $estimate = new Services_Shipping_Driver_UPS_ShipmentEstimate($nodes->item(0), $shipment);
        //$id     = $xpath->evaluate('string(//ShipmentConfirmResponse/ShipmentIdentificationNumber/text())');
        $digest = $xpath->evaluate('string(//ShipmentConfirmResponse/ShipmentDigest/text())');
        
        $key = md5($digest);
        $_SESSION['services_shipping']['UPS'][$key] = $confirmResponse->saveXML();
        
        // TODO this should return a shipmentreceipt not the digest!
        return $key;
    }
    
    public function accept($key)
    {
    	//$key = md5($digest);
    	$xml = $_SESSION['services_shipping']['UPS'][$key];
    	
    	if (empty($xml)) {
    		trigger_error("Unkown digest key [$key]");
    		var_dump($_SESSION);
    		return false;
    	}
    	
    	try{
    		$confirmResponse = DOMDocument::loadXML($xml);
    	} catch (Exception $e) {
    		trigger_error("Unable to parse saved estimate XML: {$e->getMessage()}");
    		
    		return false;
    	}
    	
        $xpath = new DOMXPath($confirmResponse);
        $nodes = $xpath->query('//ShipmentConfirmResponse/ShipmentCharges');
        
        if ($nodes === false || $nodes->length <= 0) {
            throw new LogicException("Expected ShipmentCharges from XML response");
        }
        
        $shipment = null; // TODO fix lack of shipment hint in UPS
        $estimate = new Services_Shipping_Driver_UPS_ShipmentEstimate($nodes->item(0), $shipment);
        //$id     = $xpath->evaluate('string(//ShipmentConfirmResponse/ShipmentIdentificationNumber/text())');
		$digest = $xpath->evaluate('string(//ShipmentConfirmResponse/ShipmentDigest/text())');
		
        $acceptRequestNode = Services_Shipping_XML::struct('ShipmentAcceptRequest', array(
            'Request' => array(
                'TransactionReference' => array(
                    'CustomerContext' => '',
                    'XpciVersion' => '1.0001'
                ),
                'RequestAction' => 'ShipAccept'
            ),
            
            'ShipmentDigest' => $digest
        ), $acceptRequest);
        
        //try {
        	$acceptResponse = $this->request($this->environment['shipAccept'], $acceptRequest);
        //} catch(ShippingException $e) {
        //	trigger_error("Bad shipment digest [$digest]");
        	
        //	return false;
        //}
        
        $xpath = new DOMXpath($acceptResponse);
        $nodes = $xpath->query("//ShipmentAcceptResponse");
        
        foreach ($nodes as $root) {
        	unset($_SESSION['services_shipping']['UPS'][$digest]);
        	
            return new Services_Shipping_Driver_UPS_ShipmentReceipt($root, $estimate);
        }
        
        throw new LogicException("Expected ShipmentAcceptResponse");
    }
    
    /**
     * Gets a ShipmentEstimate for a shipment.
     *
     * @param Services_Shipping_Shipment $shipment the shipment to estimate
     * 
     * @return Services_Shipping_ShipmentEstimate
     */
    public function quote(Services_Shipping_Shipment $shipment)
    {
        $ratingRequestNode = Services_Shipping_XML::struct('RatingServiceSelectionRequest', array(
            '@xml:lang' => 'en-US',
             'Request' => array(
                'TransactionReference' => array(
                    'CustomerContext' => 'none',
                    'XpciVersion' => '1.0001'
                ),
            'RequestAction' => 'Rate',
            'RequestOption' => 'Rate'
            ),
        ), $ratingRequest);
        
        $shipmentData = $this->shipmentData($shipment);
        $shipmentData['Service'] = array('Code' => $shipment->getService());
        $shipmentNode = $this->shipmentXML($shipment, $ratingRequest, $shipmentData);
        //unset($shipmentData);
        
        $ratingRequestNode->appendChild($shipmentNode);
        $ratingResponse = $this->request($this->environment['rate'], $ratingRequest);
        //$ratingResponseNode = $this->getRootNode($ratingResponse, 'RatingServiceSelectionResponse');
        
        $xpath = new DOMXPath($ratingResponse);
        $nodes = $xpath->query('//RatingServiceSelectionResponse/RatedShipment');
        
        foreach ($nodes as $ratedShipmentNode) {
            return new Services_Shipping_Driver_UPS_ShipmentEstimate($ratedShipmentNode, $shipment);
        }
        
        throw new LogicException("Expected a RatedShipment in XML response");
    }
    
    /**
     * Similar to {@link #quote()}, except it returns
     * an array of ShipmentEstimtes for a variety of UPS
     * services. This avoids quoting the same shipment for
     * different services.
     *
     * @param Shipment $shipment the shipment to shop for estimates
     * 
     * @return array estimates for the shipment with different services
     */
    public function shop(Services_Shipping_Shipment $shipment)
    {
        $ratingRequestNode = Services_Shipping_XML::struct('RatingServiceSelectionRequest', array(
            '@xml:lang' => 'en-US',
            'Request' => array(
                'TransactionReference' => array(
                    'CustomerContext' => 'none',
                    'XpciVersion' => '1.0001'
                ),
                'RequestAction' => 'Rate',
                'RequestOption' => 'Shop'
            ),
        ), $ratingRequest);
        
        $shipmentNode = $this->shipmentXML($shipment, $ratingRequest);
        $ratingRequestNode->appendChild($shipmentNode);
        $ratingResponse = $this->request($this->environment['rate'], $ratingRequest);
        
        $xpath     = new DOMXPath($ratingResponse);
        $estimates = array();
        $expr      = '//RatingServiceSelectionResponse/RatedShipment';
        
        foreach ($xpath->query($expr) as $ratedShipmentNode) {
            $estimate = new Services_Shipping_Driver_UPS_ShipmentEstimate($ratedShipmentNode, $shipment);
            
            $estimates[$estimate->getShipment()->getService()] = $estimate;
        }
        
        return $estimates;
    }
    
    /**
     * Get a ShipmentTracking of a shipment using a UPS tracking identifier.
     *
     * @param string $id the tracking identifier
     * 
     * @return Services_Shipping_ShipmentEstimate the estimate object
     */
    public function track($id)
    {
        $request = Services_Shipping_XML::struct('TrackRequest', array(
            'Request' => array(
                'RequestAction' => 'Track',
                'RequestOption' => 'Activity',
                'TransactionReference' => array(
                    'CustomerContext' => 'I like money',
                    'XpciVersion' => '1.0'
                )
            ),
            'TrackingNumber' => $id
        ));
        
        $response = $this->request($this->environment['track'], $request);
        $xpath    = new DOMXPath($response);
        
        foreach ($xpath->query('Shipment') as $node) {
            return new Services_Shipping_Driver_UPS_ShipmentTracking($node);
        }
        
        throw new LogicException("Expected a shipment tracking response");
    }
}
?>