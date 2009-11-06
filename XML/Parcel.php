<?php

/**
 * Classes that describe fragments of shipments that use XML
 * data sources.
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
require_once 'Services/Shipping/Parcel.php';

/**
 * A Parcel that uses XML data.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Package
 */
abstract class Services_Shipping_XML_Parcel
    extends    Services_Shipping_XML_Source
    implements Services_Shipping_Parcel
{
    /**
     * Construct this Parcel as an XML data source.
     *
     * @param DOMNode $node the XML node to wrap
     * @param array   $map  the XPath expressions for $node
     * @param mixed   $hint an object to use if the XML data
     *        doesn't exist.
     */
    public function __construct(DOMNode $node, array $map, $hint = null)
    {
        parent::__construct($node, $map, $hint);
    }
    
    /**
     * Converts this Parcel to a string. This includes it's description.
     *
     * @return string this object as a string
     */
    public function __toString()
    {
        return "{$this->getDescription()}";
    }
    
    /**
     * Creates a ShippingParty instance for the specified
     * result of an XPath expression.
     *
     * @param mixed $value the result of an XPath expression
     * 
     * @return Services_Shipping_Party|null the party object
     */
    protected abstract function createParty($value);
    
    /**
     * Creates an array of parcel features from the result of
     * an XPath expression.
     *
     * @param mixed $value the XPath result
     * 
     * @return array an array of parcel features
     */
    protected abstract function createParcelFeatures($value);
    
    /**
     * Gets the description of this Parcel. This uses the
     * 'Description' XPath expression.
     *
     * @return string the description
     */
    public function getDescription()
    {
        return $this->get('Description');
    }
    
    /**
     * Gets the features for this Parcel. This uses the
     * 'Features' XPath expression and {@link #createParcelFeatures()}.
     *
     * @return array the features
     */
    public function getFeatures()
    {
        $features = array();
        $value    = $this->get('Features', $hinted);
        
        if ($hinted) {
            return $value;
        }
        
        return $this->createParcelFeatures($value);
    }
}
?>