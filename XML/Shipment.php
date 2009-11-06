<?php

/**
 * Classes for Shipments that use XML data sources.
 * 
 * PHP versions 5.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @copyright  2008 Santiance Corporation
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @version    CVS: $Id:$
 * @link       http://www.santiance.com/open/Services_Shipping
 */

require_once 'Services/Shipping/XML/Source.php';
require_once 'Services/Shipping/Shipment.php';

/**
 * A Shipment that uses an XML data source.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Shipment
 */
abstract class Services_Shipping_XML_Shipment
    extends    Services_Shipping_XML_Source
    implements Services_Shipping_Shipment
{
    /**
     * Constructs this Shipment as an XML data source. If a hint
     * is provided it will be used where data is not available by
     * the XML data.
     *
     * @param DOMNode $node the XML node to wrap
     * @param array   $map  the XPath mappings for the XML node
     * @param boolean $hint an optional hint to use for values
     */
    public function __construct(DOMNode $node, array $map, $hint = null)
    {
        parent::__construct($node, $map, $hint);
    }
    
    /**
     * Creates an array that contains the contents of this Shipment.
     * The 'Contents' XPath expression typically will pass a DOMNodeList
     * to this.
     *
     * @param mixed $result the result of the XPath expression for 'Contents'
     * 
     * @return array an array of {@link Services_Shipping_Parcel}s.
     */
    protected abstract function createContents($result);
    
    /**
     * Creates an array that describes the features of this Shipment. The
     * 'Features' XPath expression typically will pass a DOMNodeList.
     *
     * @param mixed $result the result of an XPath expression for 'Features'
     * 
     * @return array an array of features keyed by their feature name
     */
    protected abstract function createFeatures($result);
    
    /**
     * Creates a Party object from the result of an XPath
     * expression.
     *
     * @param mixed $result the XPath expression result from
     *        'Sender' or 'Recipient'
     * 
     * @return Services_Shipping_Party|null the party object
     */
    protected abstract function createParty($result);
    
    /**
     * Gets a party be evaluating an XPath expression or
     * checking the available hint. If neither are available
     * null is returned.
     *
     * @param string $field the field to get a party from
     * 
     * @return Services_Shipping_Party|null the party object or null
     */
    private function _getParty($field)
    {
        $party = $this->get($field, $hinted);
        
        if (empty($hinted)) {
            return $party;
        }
        
        return $this->createParty($party);
    }
    
    /**
     * Gets the contents of this Shipment as an array of Parcel objects.
     * This uses {@link #createContents} with the 'Contents' XPath
     * expression.
     *
     * @return array the contents of this Shipment
     */
    public function getContents()
    {
        $contents = $this->get('Contents', $hinted);
        
        if ($hinted) {
            return $contents;
        }
        
        return $this->createContents($contents);
    }
    
    /**
     * Gets the sending party of this Shipment. This uses
     * {@link #createParty()} with the 'Sender' XPath
     * expression.
     *
     * @return Services_Shipping_Party|null the party object
     */
    public function getSender()
    {
        return $this->_getParty('Sender');
    }
    
    /**
     * Gets the sending party of this Shipment. This uses
     * {@link #createParty()} with the 'Sender' XPath
     * expression.
     *
     * @return Services_Shipping_Party|null the party object
     */
    public function getRecipient()
    {
        return $this->_getParty('Recipient');
    }
    
    /**
     * Gets the description of this Shipment. This uses
     * the 'Description' XPath expression.
     *
     * @return string|null the description.
     */
    public function getDescription()
    {
        return $this->get('Description');
    }
    
    /**
     * Gets the service for this Shipment. This uses
     * the 'Service' XPath expression.
     *
     * @return string|null the service
     */
    public function getService()
    {
        return $this->get('Service');
    }
    
    /**
     * Gets the features for this Shipment. This uses
     * the 'Features' XPath expression and
     * {@link #createFeatures()}.
     *
     * @return array|null the features of this Shipment
     */
    public function getFeatures()
    {
        $features = $this->get('Features', $hinted);
        
        if ($hinted) {
            return $features;
        }
        
        return $this->createFeatures($features);
    }
    
    /**
     * Checks if this Shipment has a feature. This just
     * uses {@link #getFeatures()} and checks if the
     * array contains the $feature.
     *
     * @param string $feature the feature to check for
     * 
     * @return boolean true if this Shipment has that $feature
     */
    public function hasFeature($feature)
    {
        $features = $this->getFeatures();
        
        return !empty($features[$feature]);
    }
}
?>