<?php

/**
 * Classes about the collection parcels as as a single
 * entity.
 * 
 * PHP versions 5.
 * 
 * @category  Services
 * @package   Services_Shipping
 * @author    Kristopher Ives <nullmind@gmail.com>
 * @copyright 2008 Santiance Corporation
 * @license   LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @version   CVS: $Id:$
 * @link      http://www.santiance.com/open/Services_Shipping
 */


/**
 * A collection of Parcels for a carrier with information
 * about the service, sender, recipient, and features.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Shipment
 */
interface Services_Shipping_Shipment
{
    /**
     * Gets the contained {@link Parcel}s of this shipment.
     * 
     * @return array the shipment contents
     */
    public function getContents();
    
    /**
     * Gets the sending party.
     * 
     * @return ShippingParty the sending party
     */
    public function getSender();
    
    /**
     * Get the receiving party.
     * 
     * @return ShippingParty the receiving party
     */
    public function getRecipient();
    
    /**
     * Gets a description of this shipment. This may be printed
     * on a shipping label by the carrier.
     * 
     * @return string the description
     */
    public function getDescription();
    
    /**
     * Gets the service being used for this shipment.
     * 
     * @return ShippingService the service
     */
    public function getService();
    
    /**
     * Gets the features of this shipment.
     * 
     * @return array the shipment features data keyed by the
     *         {@link ShipmentFeature#getKey()}
     */
    public function getFeatures();
    
    /**
     * Checks if this shipment has the specified feature.
     * 
     * @param string $feature the identifier of the {@link ShipmentFeature}
     * 
     * @return boolean true if tis Shipment has that $feature
     */
    public function hasFeature($feature);
}

/**
 * A handler for transforming features of a Shipment
 * for specific carrier implementations. If a carrier
 * supports a feature it may implement it as this
 * interface.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Shipment
 */
interface Services_Shipping_ShipmentFeature
{
    /**
     * Gets an identifier unique to this shipment feature.
     * 
     * @return string the identifier
     */
    public function getKey();
    
    /**
     * Applies the feature by transforming the specified data.
     * 
     * @param mixed $conf  the 'features' data of the shipment
     * @param mixed &$data data to transform; this is carrier specific.
     * 
     * @return void
     */
    public function apply($conf, &$data);
}

/**
 * A default implementation of a Shipment. All the data is stored as
 * public members and the "get" methods simply return the values.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Shipment
 */
class Services_Shipping_DefaultShipment
    implements Services_Shipping_Shipment
{
    /**
     * The parcels of this shipment. See
     * {@link Services_Shipping_Shipment#getContents()}.
     *
     * @var array|null
     */
    protected $contents;
    
    /**
     * The sender of this Shipment. See
     * {@link Services_Shipping_Shipment#getSender()}.
     *
     * @var Services_Shipping_Party|null
     */
    protected $sender;
    
     /**
     * The recipient of this Shipment. See
     * {@link Services_Shipping_Shipment#getRecipient()}.
     *
     * @var Services_Shipping_Party|null
     */
    protected $recipient;
    
     /**
     * A description of this Shipment. See
     * {@link Services_Shipping_Shipment#getDescription()}.
     *
     * @var string
     */
    protected $description;
    
    /**
     * The service of this Shipment. See
     * {@link Services_Shipping_Shipment#getService()}.
     *
     * @var mixed
     */
    protected $service;
    
    /**
     * The features of this Shipment. See
     * {@link Services_Shipping_Shipment#getFeatures()}.
     *
     * @var array|null
     */
    protected $features = array();
    
    /**
     * Constructs this DefaultShipment with the specified options.
     *
     * @param array $options the options See {@link Services_Shipping_Shipment}
     *        for keys/values.
     */
    public function __construct($options = null)
    {
        if (!isset($options)) {
            return;
        }
        
        foreach (array('contents', 'sender', 'recipient') as $key) {
            $value =& $options[$key];
            
            if (!isset($value)) {
                throw new Services_Shipping_Exception("Missing required: '$key'");
            }
            
            $this->$key = $value;
        }
        
        foreach (array('description', 'features', 'service') as $key) {
            $value =& $options[$key];
            
            if (!isset($value)) {
                continue;
            }
            
            $this->$key = $value;
        }
    }
    
    /**
     * Gets a description of this Shipment. See
     * {@link #$description}.
     *
     * @return string the value of the member variable
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Gets a description of this Shipment. See
     * {@link #$description}.
     *
     * @return string the value of the member variable
     */
    public function getContents()
    {
        return $this->contents;
    }
    
    /**
     * Gets a receiving Party of this Shipment. See
     * {@link #$recipient}.
     *
     * @return Services_Shipping_Party the value of the member
     *         variable
     */
    public function getRecipient()
    { 
        return $this->recipient;
    }
    
    /**
     * Gets the sending Party of this Shipment. See
     * {@link #$sender}.
     *
     * @return Services_Shipping_Party the value of the member
     *         variable
     */
    public function getSender()
    {
         return $this->sender; 
    }
    
    /**
     * Gets a service for this Shipment. See
     * {@link #$service}.
     *
     * @return string the value of the member variable
     */
    public function getService()
    {
        return $this->service; 
    }
    
    /**
     * Gets an array of features this Shipment has. See
     * {@link #$features}.
     *
     * @return array|null the value of the member variable
     */
    public function getFeatures()
    {
        return $this->features;
    }
    
    /**
     * Checks if this Shipment has a feature. This just checks
     * {@link #$features} for the value.
     *
     * @param string $feature the feature to check for
     * 
     * @return boolean true if this Shipment has that feature
     */
    public function hasFeature($feature)
    {
        return !empty($features[$feature]);
    }
}
?>