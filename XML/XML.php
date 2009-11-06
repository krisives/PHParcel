<?php

/**
 * Base classes that provide an easy way to implement most
 * XML-based carriers.
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

require_once 'PEAR.php';

require_once 'Services/Shipping.php';

require_once 'Services/Shipping/XML/Party.php';
require_once 'Services/Shipping/XML/Address.php';
require_once 'Services/Shipping/XML/Parcel.php';
require_once 'Services/Shipping/XML/Letter.php';
require_once 'Services/Shipping/XML/Package.php';
require_once 'Services/Shipping/XML/Shipment.php';
require_once 'Services/Shipping/XML/ShipmentLabel.php';
require_once 'Services/Shipping/XML/ShipmentEstimate.php';
require_once 'Services/Shipping/XML/ShipmentTracking.php';
require_once 'Services/Shipping/XML/ShipmentReceipt.php';
require_once 'Services/Shipping/XML/Carrier.php';

/**
 * A utility class for XML operations.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/XML
 */
abstract class Services_Shipping_XML
{
    /**
     * Constructs an DOMNode from data in an array, where
     * each entry in the array is either another array with this structure
     * or an attribute. Attributes are prefixed with
     * '@'.
     * 
     * @param string      $tagName   the nodeValue of the DOMNode to create
     * @param mixed       $data      data to put into the created node.
     * @param DOMDocument &$document the document to use. if NULL it will
     *        be created.
     * @param boolean     $append    true if the node should be inserted
     *        into the DOMDocument
     * 
     * @return DOMNode the created node. If document wasn't specified
     *         this may be a return type of DOMDocument (which is a DOMNode)
     */
    public static function struct($tagName, $data, &$document = null, $append = true)
    {
        if ($document == null) {
            $isDocument = true;
            $document   = new DOMDocument();
        }
        
        if (is_array($data)) {
            $node = $document->createElement($tagName);
            
            foreach ($data as $key => $value) {
                if ($key[0] == '@') {
                    $key = substr($key, 1);
                    $node->setAttribute($key, $value);
                } else {
                    $child = Services_Shipping_XML::struct($key, $value, $document);
                    $node->appendChild($child);
                }
            }
        } else {
            $node = $document->createElement($tagName, $data);
        }
        
        if ($isDocument && $append) {
            $document->appendChild($node);
        }
        
        return $node;
        //return $isDocument ? $document : $node;
    }
}
?>