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

require_once 'Services/Shipping/XML/ShipmentReceipt.php';

/**
 * A receipt for a Shipment transaction with UPS. Use
 * {@link Services_Shipping_Driver_UPS#ship()} on a UPS carrier to
 * make a shipment and get a receipt.
 * 
 * There are multiple request and response XML nodes when making
 * a UPS shipment. This class wraps the ShipmentConfirmResponse node. The
 * estimate object is passed to this class because it wraps a
 * ShipmentAcceptResponse.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/ShipmentReceipt
 */
class Services_Shipping_Driver_UPS_ShipmentReceipt
    extends Services_Shipping_XML_ShipmentReceipt
{
    /**
     * XPath mappings for the XML nodes that may be wrapped. The lower-case nodeName
     * is used as a key with the XPath expression as the values.
     *
     * @var array
     */
    protected static $mappings = array(
        'shipmentacceptresponse' => array(
            'TrackingId' => 'string(//ShipmentAcceptResponse/ShipmentResults/PackageResults/TrackingNumber/text())',
            'Label' => '//ShipmentAcceptResponse/ShipmentResults/PackageResults/LabelImage[1]'
        )
    );
    
    /**
     * The estimate UPS gave us for the shipment before accepting and
     * generating the label. This must be passed to the constructor.
     * 
     * @var Services_Shipping_ShipmentReceipt
     */
    protected $estimate;
    
    /**
     * Constructs this ShipmentReceipt by wrapping the provided XML node. The node
     * should be a ShipmentConfirmResponse. The estimate should wrap the
     * ShipmentAcceptResponse node, but may be any ShipmentEstimate object.
     *
     * @param DOMNode                            $node     the XML node to wrap
     * @param Services_Shipping_ShipmentEstimate $estimate the estimate of the
     *        Shipment
     */
    public function __construct(DOMNode $node, Services_Shipping_ShipmentEstimate $estimate)
    {
        $key = strtolower($node->nodeName);
        $mapping =& self::$mappings[$key];
        
        if (!isset($mapping)) {
            throw new IllegalArgumentException("Not expecting '{$node->nodeName}' XML node");
        }
        
        parent::__construct($node, $mapping);
        
        $this->estimate = $estimate;
    }
    
    /**
     * Gets the estimate of the shipment this receipt is for.
     *
     * @return Services_Shipping_ShipmentEstiamte the estimate object
     */
    public function getEstimate()
    {
        return $this->estimate;
    }
    
    /**
     * Creates a UPS ShipmentLabel object from an XPath expression result, which
     * should be a LabelImage node.
     *
     * @param mixed $value the result of the XPath expression
     * 
     * @return Services_Shipping_ShipmentLabel the label object
     */
    protected function createLabel($list)
    {
        $node = $list->item(0);
        
        return new Services_Shipping_Driver_UPS_ShipmentLabel($node);
    }
    
    /**
     * Creates a ShipmentEstimate from an XPath expression. This
     * is unused for the UPS carrier because the receipt gives the
     * estimate in a different XML response.
     *
     * @param mixed $value the XPath expression result
     * 
     * @return Services_Shipping_ShipmentEstimate the estimate object
     * 
     * @throws IllegalStateException because this is invalid and shouldn't be used
     */
    protected function createEstimate($value)
    {
        throw new IllegalStateException("Invalid createEstimate for UPS ShipmentReceipt");
    }
}
?>