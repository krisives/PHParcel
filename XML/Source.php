<?php

/**
 * Base classes for XML-backed shipping objects.
 * 
 * PHP versions 5.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @copyright  2008 Santiance Corporation
 * @license    LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @version    CVS: $Id:$
 * @link       http://www.santiance.com/open/Services_Shipping
 */

require_once 'Services/Shipping/XML.php';

/**
 * An object that provides get methods that evaluate
 * {@link http://us2.php.net/manual/en/class.domxpath.php XPath}
 * expressions. An optional reference to a hint object may be provided,
 * which getters will be used if there is no XPath entry.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/XML
 */
abstract class Services_Shipping_XML_Source
{
    /**
     * DOMNode that serves as the root of XPath queries.
     *
     * @var DOMNode
     */
    protected $node;
    
    /**
     * XPath expression strings to be evaluated. The keys are
     * the name of the get method. For example the key "Value"
     * will be used for a getValue method.
     *
     * @var array
     */
    protected $map;
    
    /**
     * DOMXPath object used for querying the document that contains
     * this XML shipment's node.
     *
     * @var DOMXPath
     */
    protected $xpath;
    
    /**
     * An object that may be used for hints if there is no XPath expression
     * for a given field.
     * 
     * @var mixed
     */
    protected $hint;
    
    /**
     * Constructs this XML data source.
     *
     * @param DOMNode $node the node to wrap
     * @param array   $map  the XPath expressions, keyed by the field name
     * @param mixed   $hint an object to use for hints
     */
    protected function __construct(DOMNode $node, array $map, $hint = null)
    {			
        $this->node = $node;
        $this->map  = $map;
        
        if ($node instanceof DOMDocument) {
            $owner = $node;
        } else {
            $owner = $node->ownerDocument;
        }
        
        $this->xpath = new DOMXPath($owner);
        $this->hint  = $hint;
    }
    
    /**
     * Checks if this XML source has a field.
     *
     * @param string $field the field to check for
     * 
     * @return boolean true if the XML source contains the
     *         field; false if the field is not available or
     *         is provided by a hint object.
     */
    protected function has($field)
    {
        return array_key_exists($field, $this->map);
    }
    
    /**
     * Gets a field of information for this 
     * by evaluating an XPath expression or using the
     * hint.
     * 
     * @param string  $field   the field to get
     * @param boolean &$hinted set to true if the field was provided by the
     *        hinted data instead of the XML data source
     * 
     * @return mixed the XPath evaluation or hinted Shipment field value
     */
    protected function get($field, &$hinted = false)
    {
        $expression = &$this->map[$field];
        
        if (empty($expression)) {
            if ($this->hint != null) {
                $method = "get$field";
                $value  = $this->hint->$method();
            
                $hinted = true;
                return $value;
            }
            
            $msg = "Trying to access hinted value '$field' without hint";
            trigger_warning($msg, E_USER_WARNING);
            return;
        }
        
        return $this->xpath->evaluate($expression, $this->node);
    }
}
?>