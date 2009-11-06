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
require_once 'Services/Shipping/ShipmentEstimate.php';

/**
 * A ShipmentEstiamte that uses an XML data source.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/ShipmentEstimate
 */
abstract class Services_Shipping_XML_ShipmentEstimate
    extends    Services_Shipping_XML_Source
    implements Services_Shipping_ShipmentEstimate
{
    //protected $shipments;
    //protected $shipmentNodes;
    
    /**
     * Constructs this ShipmentEstimate as an XML data source.
     *
     * @param DOMNode $node the XML node to wrap
     * @param array   $map  the XPath mappings for the node
     * @param mixed   $hint an object that may be used if the XML
     *        data doesn't exist.
     */
    public function __construct(DOMNode $node, array $map, $hint = null)
    {
        parent::__construct($node, $map, $hint);
    }
    
    /**
     * Creates a Shipment from a result of an XPath expression
     * that expects a Shipment.
     *
     * @param mixed $result the XPath expression result
     * 
     * @return Services_Shipping_Shipment|null the created shipment object
     */
    protected abstract function createShipment($result);
    
    /**
     * Gets the shipment this estimate is for. This uses the
     * 'Shipment' XPath expression and {@link #createShipment()}.
     *
     * @return Services_Shipping_Shipment|null the shipment object
     */
    public function getShipment()
    {
        $shipment = $this->get('Shipment', $hinted);
        
        if ($hinted) {
            return $shipment;
        }
        
        return $this->createShipment($shipment);
    }
    
    // TODO document how currency of different types works
    // TODO unsimplify this
    
    /**
     * Gets the total cost of the shipment being estimated. This uses
     * the 'TotalCost' XPath expression.
     *
     * @return float the total cost
     */
    public function getTotalCost()
    {
        return $this->get('TotalCost');
    }
    
    /**
     * Gets the total estimated time. This uses the 'TotalTime' XPath
     * expression.
     *
     * @return float the total time. See
     *         {@link Services_Shipping_ShipmentEstimate#getTotalTime()}.
     */
    public function getTotalTime()
    {
        return $this->get('TotalTime');
    }
}
?>