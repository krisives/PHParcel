<?php

/**
 * Classes for the label generation for UPS.
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

require_once 'Services/Shipping/XML/ShipmentLabel.php';

/**
 * A ShipmentLabel for UPS. It wraps a LabelImage XML
 * node. Information about the image data and format is
 * available using getImageData and getImageFormat.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Shipment
 */
class Services_Shipping_Driver_UPS_ShipmentLabel
    extends Services_Shipping_XML_ShipmentLabel
{
    /**
     * XPath mappings for the LabelImage node. The array is keyed
     * by the lower-case nodeName. The values are a mapping to
     * XPath expressions.
     *
     * @var array
     */
    protected static $mappings = array(
        'labelimage' => array(
            'ImageData' => 'string(GraphicImage/text())',
            'ImageFormat' => 'string(LabelImageFormat/Code/text())'
         )
    );
    
    /**
     * Constructs this ShipmentLabel from a UPS response LabelImage XML
     * node.
     *
     * @param DOMNode $node the XML node
     * 
     * @throws IllegalArgumentException if there is no mapping for the node
     */
    public function __construct(DOMNode $node)
    {
        $key = strtolower($node->nodeName);
        $map =& self::$mappings[$key];
        
        if (!isset($map)) {
            throw new InvalidArgumentException("Not expecting {$node->nodeName} XML node");
        }
        
        parent::__construct($node, $map);
    }
    
    /**
     * Gets the data for the generated label. This transforms the
     * base64 encoded XML data into a binary string using the
     * base64_decode PHP function.
     *
     * @return string|null the image data as a binary string
     */
    public function getImageData()
    {
        return base64_decode(parent::getImageData());
    }    
}
?>