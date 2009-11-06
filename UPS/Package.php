<?php

/**
 * Classes for the package parcels for UPS.
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

require_once 'Services/Shipping/UPS/Parcel.php';
require_once 'Services/Shipping/Package.php';

/**
 * A Package for UPS that uses XML for data. It extends an XML base
 * class.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Package
 */
class Services_Shipping_Driver_UPS_Package
    extends Services_Shipping_XML_Package
{
    /**
     * XPath mappings
     *
     * @var array
     */
    protected static $mappings = array(
        'ratedpackage' => array(
        	'Weight' => 'number(BillingWeight/Weight/text())'
        )
    );
    
    /**
     * Constructs this Package from the provided UPS response XML node.
     *
     * @param DOMNode $node the XML node to wrap
     * @param mixed   $hint an object to use if data is not present in the XML
     */
    public function __construct(DOMNode $node, $hint = null)
    {
        $key = strtolower($node->nodeName);
        $map =& self::$mappings[$key];
        
        if (empty($map)) {
            throw new InvalidArgumentException("Not expecting '{$node->nodeName}' XML node");
        }
        
        parent::__construct($node, $map, $hint);
    }
    
    /**
     * Converts this Package to a string.
     *
     * @return string this as a string
     */
    public function __toString()
    {
        return "UPS Package ({$this->getWeight()}lbs) ".parent::__toString();
    }
    
    /**
     * Creates a Party from an XPath expression result. This
     * returns a UPS Party.
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
     * Creates an array of features for this Parcel from an
     * XPath expression result.
     *
     * @param mixed $value the XPath expression result
     * 
     * @return array|null the parcel features
     */
    protected function createParcelFeatures($value)
    {
        return $value;  
    }
}
?>