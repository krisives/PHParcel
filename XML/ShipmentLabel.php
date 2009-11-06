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
require_once 'Services/Shipping/ShipmentLabel.php';

/**
 * A ShipmentLabel that uses XML data.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Address
 */
abstract class Services_Shipping_XML_ShipmentLabel
    extends    Services_Shipping_XML_Source
    implements Services_Shipping_ShipmentLabel
{
    /**
     * Constructs this ShipmentLabel as an XML data source.
     *
     * @param DOMNode $node the XML node to wrap
     * @param array   $map  the XPath expressions for the node
     */
    public function __construct(DOMNode $node, array $map)
    {
        parent::__construct($node, $map);
    }
    
    /**
     * Gets the data of the image for this ShipmentLabel. This
     * evaluates the 'ImageData' XPath expression.
     *
     * @return mixed the image data for the label
     */
    public function getImageData()
    {
        return $this->get('ImageData');
    }
    
    /**
     * Gets the format of the image for this ShipmentLabel. This
     * evaluates the 'ImageFormat' XPath expression.
     *
     * @return string|null the image format
     */
    public function getImageFormat()
    {
        return $this->get('ImageFormat');
    }
}
?>