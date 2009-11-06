<?php

/**
 * Classes for XML-based package parcels.
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

require_once 'Services/Shipping/XML/Parcel.php';

/**
 * A Package parcel that uses XML data.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Package
 */
abstract class Services_Shipping_XML_Package
    extends    Services_Shipping_XML_Parcel
    implements Services_Shipping_Package
{
    /**
     * Constructs this Package as an XML data source.
     *
     * @param DOMNode $node the XML node to wrap
     * @param array   $map  the XPath expressions for $node
     */
    public function __construct(DOMNode $node, array $map)
    {
        parent::__construct($node, $map);
    }
    
    /**
     * Gets this Package as a string. This includes the
     * weight and dimensions.
     *
     * @return string this as a string
     */
    public function __toString()
    {
        return parent::__toString()." ({$this->getWeight()}lbs)";
    }
    
    /**
     * Gets the weight of this Package. This uses the 'Weight'
     * XPath expression.
     *
     * @return float the package weight
     */
    public function getWeight()
    {
        return $this->get('Weight');
    }
    
    /**
     * Gets the dimensions of this Package in an array. This uses the
     * 'Dimensions' XPath expression and {@link #createDimensions()}.
     *
     * @return array|null the dimensions as an array with the keys
     *         'width', 'length', and 'height' containing float
     *         values.
     */
    public function getDimensions()
    {
        return $this->get('Dimensions');
    }
}
?>