<?php

/**
 * Classes for tracking shipments.
 * 
 * PHP versions 5.
 * 
 * @category  Services
 * @package   Services_Shipping
 * @author    Kristopher Ives <nullmind@gmail.com>
 * @copyright 2008 Santiance Corporation
 * @license   LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @version   CVS: $Id:$
 * @link      http://www.santiance.com/open/Services_Shipping
 */


/**
 * Describes the status of a Shipment.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/ShipmentTracking
 */
interface Services_Shipping_ShipmentTracking
{
    /**
     * Gets the tracking identifier. This should be in the same
     * format as {@link Services_Shipping_Carrier#track()}.
     * 
     * @return string the tracking identifier
     */
    public function getTrackingId();
    
    /**
     * Gets the status of the shipment for this tracking. This
     * is carrier specific.
     * 
     * @return string the shipment status
     */
    public function getStatus();
}
?>