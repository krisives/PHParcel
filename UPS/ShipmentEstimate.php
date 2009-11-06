<?php

/**
 * Classes for the estimation of shipments for UPS.
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
require_once 'Services/Shipping/XML/ShipmentEstimate.php';

/**
 * A ShipmentEstimate for UPS that wraps the a RatedShipment or ShipmentCharges
 * node.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Shipment
 */
class Services_Shipping_Driver_UPS_ShipmentEstimate
    extends Services_Shipping_XML_ShipmentEstimate
{
    /**
     * An array of mappings for the RatedShipment and ShipmentCharges
     * node.
     *
     * @var array
     */
    protected static $mappings = array(
        'ratedshipment' => array(
            'TotalCost' => 'string(TotalCharges/MonetaryValue/text())',
            'TotalTime' => 'string(GuaranteedDaysToDelivery/text())',
            'Shipment' => '.'
        ),
        
        'shipmentcharges' => array(
            'TotalCost' => 'string(TotalCharges/MonetaryValue/text())'
        )
    );
    
    /**
     * The shipment being estimated. UPS doesn't give enough information
     * in the XML responses.
     *
     * @var Services_Shipping_ShipmentEstimate
     */
    protected $shipment;
    
    /**
     * Constructs this ShipmentEstimate from an UPS response XML node.
     *
     * @param DOMNode                    $node     the XML node to wrap
     * @param Services_Shipping_Shipment $shipment the shipment being estimated
     * 
     * @throws IllegalArgumentException if the node isn't a RatedShipment or ShipmentCharges
     */
    public function __construct(DOMNode $node, /* TODO Services_Shipping_Shipment */ $shipment)
    {
        $mapping =& self::$mappings[strtolower($node->nodeName)];
        
        if (!isset($mapping)) {
            throw new InvalidArgumentException("Not expecting '{$node->nodeName}' XML node");
        }
        
        parent::__construct($node, $mapping);
        $this->shipment = $shipment;
    }
    
    /**
     * Creats a UPS Shipment from an XPath expression result. The result
     * should be a RatedShipment node.
     *
     * @param mixed $list the XPath expression result
     * 
     * @return Services_Shipping_Shipment the shipment object
     */
    protected function createShipment($list)
    {
        $node = $list->item(0);
        
        return new Services_Shipping_Driver_UPS_Shipment($node, $this->shipment);
    }
}
?>