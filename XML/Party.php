<?php
/**
 * Classes for people and entities described by XML data.
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
require_once 'Services/Shipping/Party.php';

/**
 * A Party that uses XML data.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Shipment
 */
abstract class Services_Shipping_XML_Party
    extends    Services_Shipping_XML_Source
    implements Services_Shipping_Party
{
    /**
     * Construct this Party as an XML data source.
     *
     * @param DOMNode $node the XML node to wrap
     * @param array   $map  a mapping of XPath queries
     * @param mixed   $hint an object to use if the XML data doesn't
     *        exist for something.
     */
    public function __construct(DOMNode $node, array $map, $hint = null)
    {
        parent::__construct($node, $map, $hint);
    }
    
    /**
     * Creates an Address using the result of an XPath expression.
     *
     * @param mixed $node the result of an XPath expression
     * 
     * @return Services_Shipping_Address|null the address object
     */
    protected abstract function createAddress($node);
    
    /**
     * Gets the name of this Party. This evaluates the 'Name'
     * XPath expression.
     *
     * @return string|null the name
     */
    public function getName()
    {
        return $this->get('Name');
    }
    
    /**
     * Gets the phone number of this Party. This evaluates the
     * 'PhoneNumber' XPath expression.
     *
     * @return string|null the phone number
     */
    public function getPhoneNumber()
    {
        return $this->get('PhoneNumber');
    }
    
    /**
     * Gets the address of this Party. This evaluates the
     * 'Address' XPath expression and uses {@link #createAddress()}.
     *
     * @return Services_Shipping_Address|null the address object
     */
    public function getAddress()
    {
        return $this->createAddress($this->get('Address'));
    }
}
?>