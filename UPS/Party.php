<?php

/**
 * Classes that describe people or things that use shipping services with
 * the UPS carrier.
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

require_once 'Services/Shipping/XML/Party.php';

/**
 * A shipping Party for the UPS carrier. It uses XML for data, so it
 * extends the XML Party base class.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Party
 */
class Services_Shipping_Driver_UPS_Party extends Services_Shipping_XML_Party
{
    /**
     * XPath expression mappings for XML node.
     *
     * @var array
     */
    protected static $mapping = array(
        'Name' => 'string(CompanyName/text())',
        'PhoneNumber' => 'string(PhoneNumber/text())',
        'Address' => 'Address'
    );
    
    /**
     * Constructs this Party from a UPS response XML node.
     *
     * @param DOMNode $node the XML node to wrap
     * @param mixed   $hint an object to use if XML data is not present
     */
    public function __construct(DOMNode $node, $hint = null)
    {
        parent::__construct($node, self::$mapping, $hint);
    }
    
    /**
     * Creates an Address from an XPath expression result. This
     * creates a UPS address object.
     *
     * @param mixed $node the XPath expression result
     * 
     * @return Service_Shipping_Address|null the address object
     */
    protected function createAddress($node)
    {
        return new Services_Shipping_Driver_UPS_Address($node);
    }
}
?>