<?php

/**
 * Classes for tracking UPS shipments.
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

require_once 'Services/Shipping/XML/ShipmentTracking.php';

/**
 * A ShipmentTracking for UPS shipments.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/ShipmentTracking
 */
class Services_Shipping_Driver_UPS_ShipmentTracking
    extends Services_Shipping_XML_ShipmentTracking
{
    /**
     * XPath mappings for for the possible XML nodes.
     *
     * @var array
     */
    protected static $mappings = array(
         'shipment' => array(
            'Id'     => 'string(Package/TrackingNumber/text())',
            'Status' => 'string(Package/Activity/Status/StatusType/Description/text())'
         )
    );
    
    /**
     * Construct this ShipmentTracking by taking a UPS response XML node.
     *
     * @param DOMNode $node the XML node to wrap
     * 
     * @return Services_Shipping_Driver_UPS_ShipmentTracking the tracking object
     */
    public function __construct(DOMNode $node)
    {
        $key = strtolower($node->nodeName);
        $mapping =& self::$mappings[$key];
        
        if (!isset($mapping)) {
            throw new InvalidArgumentException("Not expecting {$node->nodeName} XML node");
        }
        
        parent::__construct($node, $mapping);
    }
    
    /**
     * Converts this ShipmentTracking to a string.
     *
     * @return string this as a string
     */
    public function __toString()
    {
        return "#{$this->getId()} ({$this->getStatus()})";
    }
}
?>