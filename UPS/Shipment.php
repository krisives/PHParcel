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

require_once 'Services/Shipping/XML/Shipment.php';

/**
 * A Shipment for the UPS carrier. It uses XML data, so it
 * extends the XML Shipment base class.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Shipment
 */
class Services_Shipping_Driver_UPS_Shipment
    extends Services_Shipping_XML_Shipment
{
    protected static $mappings = array(
        'ratedshipment' => array(
            'Service' => 'string(Service/Code/text())',
            'Contents' => 'RatedPackage/'
        )
    );
    
    /**
     * Constructs this Shipment from a UPS response XML node.
     *
     * @param DOMNode $node the XML node
     * @param mixed   $hint an object to use if the data is not available
     *        in the XML data
     */
    public function __construct(DOMNode $node, $hint = null)
    {
        $map = self::$mappings[strtolower($node->nodeName)];
        
        if (empty($map)) {
            throw new InvalidArgumentException("Unknown node {$node->nodeName}");
        }
        
        parent::__construct($node, $map, $hint);
    }
    
    /**
     * Creates an array of parcels for this shipment using an
     * XPath expression result.
     *
     * @param mixed $result the XPath expression result
     * 
     * @return array|null the contents of this shipment
     */
    protected function createContents($result)
    {
        throw new IllegalStateException("UPS never gives contents in XML response");
    }
    
    /**
     * Creates an array of shipment features for this shipment using an
     * XPath expression result.
     * 
     * This is never used by the UPS carrier and throws an
     * IllegalStateException. File a bug if an exception
     * gets thrown, please include a stack trace.
     *
     * @param mixed $result the XPath expression result
     * 
     * @return array|null the features of this shipment
     */
    protected function createFeatures($result)
    {
        throw new IllegalStateException("UPS never gives features in XML response");
    }
    
    /**
     * Creates a UPS Party from an XPath expression result.
     *
     * @param mixed $result an XML node
     * 
     * @return Services_Shipping_Party the party object
     */
    protected function createParty($result)
    {
        return new Services_Shipping_UPS_Driver_Party($result);
    }
}
?>