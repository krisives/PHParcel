<?php

/**
 * Addresses and locations for shipping services.
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

require_once 'Services/Shipping/Shipment.php';

/**
 * An estimate from a ShippingCarrier for a shipment. A shipping
 * estimate may describe the time or cost, or both, for a carrier
 * to ship a shipment using a specific service.
 * 
 * For example, this may describe the time and cost DHL estimates
 * it can ship a 26lb bucket from Oregon to California via
 * their Ground service.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/ShipmentEstimate
 */
interface Services_Shipping_ShipmentEstimate
{
    /**
     * Gets the total estimated cost for the shipment. The
     * default currency is USD (United States Dollars), however
     * a currency suffix may be included in the string. If the
     * currency is USD it should be ommited.
     * 
     * @return string total estimated cost (see description for currency)
     */
    public function getTotalCost();
    
    /**
     * Gets the total estimated time for the estimate.
     * 
     * @return int total estimated time in days
     */
    public function getTotalTime();
    
    /**
     * Gets the Shipment this estimate is for.
     * 
     * @return Services_Shipping_Shipment|null the shipment of this estimate;
     *         or null if the shipment is unavailable.
     */
    public function getShipment();
}
?>