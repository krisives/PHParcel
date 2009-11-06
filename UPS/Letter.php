<?php

/**
 * Classes for the letter parcels for UPS.
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

require_once 'Services/Shipping/XML/Letter.php';

/**
 * A Letter for UPS that uses XML data from an
 * XML response. 
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Letter
 */
class Services_Shipping_Driver_UPS_Letter
    extends Services_Shipping_XML_Letter
{
    /**
     * Constructs this Letter by wrapping a UPS response XML node.
     *
     * @param DOMNode $node the XML response node
     * @param mixed   $hint an object to use if data is not present
     */
    public function __construct(DOMNode $node, $hint = null)
    {
        parent::__construct($node, $hint);			
    }
    
    /**
     * Converts this Letter to a string.
     *
     * @return string this as a string
     */
    public function __toString()
    {
        return "UPS Letter ".parent::__toString();
    }
    
    /**
     * Creates a Party from an XPath expression result.
     *
     * @param mixed $value the XPath expression result
     * 
     * @return Services_Shipping_Party|null the party object
     */
    protected function createParty($value)
    {
        return new Services_Shipping_Driver_UPS_Party($value);
    }
    
    /**
     * Creates a parcel features array from an XPath expression result.
     *
     * @param mixed $value the XPath expression result
     * 
     * @return array|null the features array
     */
    protected function createParcelFeatures($value)
    {
        return $value;
    }
}
?>