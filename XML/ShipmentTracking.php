<?php

/**
 * Classes that get shipment tracking information from
 * XML data sources.
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
require_once 'Services/Shipping/ShipmentTracking.php';

/**
 * A ShipmentTracking that uses an XML data source.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/ShipmentTracking
 */
abstract class Services_Shipping_XML_ShipmentTracking
    extends    Services_Shipping_XML_Source
    implements Services_Shipping_ShipmentTracking
{
    /**
     * Constructs this ShipmentTracking as an XML source.
     *
     * @param DOMNode $node the node to wrap
     * @param array   $map  the XPath mappings
     */
    public function __construct(DOMNode $node, array $map)
    {
        parent::__construct($node, $map);
    }
    
    /**
     * Gets this ShipmentTracking as a string. The string
     * has the tracking identifier in it.
     *
     * @return string this object as a string
     */
    public function __toString()
    {
        return "Tracking ({$this->getId()})";
    }
    
    /**
     * Gets the tracking identifier be evaluating the 'TrackingId'
     * in the XPath mappings. If there is no mapping the hinted
     * ShipmentTracking is checked. Finally, if neither exist
     * null is returned.
     * 
     * This should be similar to the tracking identifier given
     * by {@link Services_Shipping_Carrier#track()}.
     *
     * @return string|null the tracking identifier.
     */
    public function getTrackingId()
    {
        return $this->get('Id');
    }
    
    /**
     * Gets the status by evaluating the 'Status' key
     * of the XPath mappings. If there is no mapping
     * the hinted ShipmentTracking is checked. Finally,
     * if neither exist null is returned.
     *
     * @return string|null the status of the shipment
     */
    public function getStatus()
    {
        return $this->get('Status');
    }
}
?>