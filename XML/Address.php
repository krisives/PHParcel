<?php

/**
 * Classes for addresses described by XML data.
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
require_once 'Services/Shipping/Address.php';

/**
 * An Address for shipping services that uses XML data.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Address
 */
class Services_Shipping_XML_Address
    extends     Services_Shipping_XML_Source
    implements  Services_Shipping_Address
{
    /**
     * Constructs a ShippingAddress that gets information from an DOMNode
     * using a mapping of DOMXPath queries.
     * 
     * @param DOMNode $node the node to wrap
     * @param array   $map  a mapping of XPath queries
     * @param mixed   $hint an optional address to provide hints (see
     *        {@link Services_Shipping_XML_Source})
     */
    public function __construct(DOMNode $node, array $map, $hint = null)
    {
        parent::__construct($node, $map, $hint);
    }
    
    /**
     * Gets the state of this Address. This uses the 'State'
     * XPath expression.
     *
     * @return string the XPath evaluation
     */
    public function getState()
    {
        return $this->get('State');
    }
    
    /**
     * Gets the city of this Address. This uses the 'City'
     * XPath expression.
     *
     * @return string the XPath evaluation
     */
    public function getCity()
    {
        return $this->get('City');
    }
    
    /**
     * Gets the country of this Address. This uses the 'Country'
     * XPath expression.
     *
     * @return string the XPath evaluation
     */
    public function getCountry()
    {
        return $this->get('Country');
    }
    
    /**
     * Gets the postal code of this Address. This uses the 'PostalCode'
     * XPath expression.
     *
     * @return string the XPath evaluation
     */
    public function getPostalCode()
    {
        return $this->get('PostalCode');
    }
    
    /**
     * Gets the street of this Address. This uses the 'Street'
     * XPath expression.
     *
     * @return string the XPath evaluation
     */
    public function getStreet()
    {
        return $this->get('Street');
    }
}
?>