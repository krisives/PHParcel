<?php

/**
 * Classes for XML-based letter parcels.
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
 * A Package implementation where data is provided by an
 * DOMNode and DOMXPath expressions.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Letter
 */
abstract class Services_Shipping_XML_Letter
    extends    Services_Shipping_XML_Parcel
    implements Services_Shipping_Letter
{
    /**
     * Construct this Letter as an XML data source.
     *
     * @param DOMNode $node the XML node to wrap
     * @param array   $map  the XPath expressions for $node
     */
    public function __construct(DOMNode $node, $map = null)
    {
        parent::__construct($node, $map);
    }
    
    /**
     * Converts this Letter to a string.
     *
     * @return string this Letter as a string
     */
    public function __toString()
    {
        return "Letter ".parent::__toString();
    }
}
?>