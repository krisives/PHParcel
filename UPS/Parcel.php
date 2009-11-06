<?php

/**
 * Classes for the receipt of a shipment.
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

require_once 'Services/Shipping/XML/Parcel.php';

/**
 * An abstract Parcel for UPS. It uses XML data so it extends the XML base class.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Parcel
 */
abstract class Services_Shipping_Driver_UPS_Parcel
    extends Services_Shipping_XML_Parcel
{
    /**
     * UPS Letter parcel type.
     */
    const LETTER = '01';
    
    /**
     * UPS Package parcel type
     */
    const PACKAGE = '02';
    
    /**
     * UPS Tube parcel type
     */
    const TUBE = '03';
    
    /**
     * UPS PAK parcel type
     */
    const PAK = '04';
    
    /**
     * UPS Express Box parcel type
     */
    const EXPRESS_BOX = '21';
    
    /**
     * A parcel type for use with Poland.
     */
    const BOX_25KG = '24';
    
    /**
     * A parcel type for use with Poland.
     *
     */
    const BOX_10KG = '25';
    
    /**
     * A Palette parcel for UPS.
     */
    const PALETTE = '30';
    
    /**
     * An array of XPath mappings keyed by their nodeName.
     *
     * @var array
     */
    protected static $mappings = array(
        'ratedshipment' => array(
            'Service' => 'string(Service/Code/text())'
        )
    );
    
    /**
     * Construct this Parcel from the provided UPS response XML node.
     *
     * @param DOMNode $node the XML node
     * @param mixed   $hint an object to use if XML data is not present
     */
    public function __construct(DOMNode $node, $hint = null)
    {
        $mapping =& self::$mappings[strtolower($node->nodeName)];
        
        if (!isset($mapping)) {
            throw new InvalidArgumentException("Not expecting {$node->nodeName}");
        }
        
        parent::__construct($node, $mapping, $hint);
    }
    
    /**
     * Converts this Parcel to a string.
     *
     * @return string this object as a string
     */
    public function __toString()
    {
        return "UPS Parcel";
    }
    
    /**
     * Creates a UPS Party from an XPath expression result.
     *
     * @param mixed $value the XPath expression result
     * 
     * @return Services_Shipping_Party|null the UPS party object
     */
    protected function createParty($value)
    {
        return new Services_Shipping_Driver_UPS_Party($value);
    }
    
    /**
     * Creates the parcel features of this Parcel from an XPath expression
     * result.
     *
     * @param mixed $value the XPath expression result
     * 
     * @return array|null an array of features
     */
    protected function createParcelFeatures($value)
    {
        return null;
    }
}
?>