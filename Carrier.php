<?php

/**
 * Classes for shipping carriers.
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
 * An entity that delivers, quotes, and tracks Shipments.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Carrier
 */
abstract class Services_Shipping_Carrier
{
    /**
     * Gets an estimate for a shipment using this carrier.
     * 
     * @param Services_Shipping_Shipment $shipment a shipment to estimate
     * 
     * @return Services_Shipping_ShipmentEstimate a shipping estimate
     */
    public abstract function quote(Services_Shipping_Shipment $shipment);
    
    /**
     * Gets an estimate for all the services that are
     * capable of handling the described shipment. By
     * default this calls {@link #quote()} with
     * {@link #getServices()}.
     * 
     * @param $shipment the shipment to estimate
     * 
     * @return array all the estimate objects keyed by their
     * service identifier.
     */
    public function shop(Services_Shipping_Shipment $shipment)
    {
        $estimates = array();
        
        foreach ($this->getServices() as $serviceId => $serviceName) {
            $shipment->service = $serviceId;
            
            $estimates[$serviceId] = $this->quote($shipment);
        }
        
        return $estimates;
    }
    
    /**
     * Gets a ShipmentTracking for a Shipment using this carrier and
     * the specified tracking identifier. This should be similar to
     * the tracking identifier of a ShipmentReceipt.
     *
     * @param string $id a tracking identifier
     * 
     * @return Services_Shipping_ShipmentTracking a tracking of the shipment
     */
    public abstract function track($id);
    
    /**
     * Ships a shipment using this carrier. A receipt, which should always
     * contain an estimate, will describe the transaction.
     *
     * @param Services_Shipping_Shipment $shipment the shipment to ship
     * 
     * @return Services_Shipping_ShipmentReceipt a shipping receipt
     */
    public abstract function ship(Services_Shipping_Shipment $shipment);
    
    /**
     * Gets all the services this carrier supports. These values
     * may be used as part of the 'service' field of a parcel.
     *
     * @return array the services names keyed by their id
     */
    public abstract function getServices();
    
    /**
     * Gets the name of a service based on it's identifier for
     * this carrier.
     *
     * @param string $id the service identifier
     * 
     * @return string|null the name of the service; or null if
     * this carrier doesn't have that service
     */
    public function getServiceName($id)
    {
        $services = $this->getServices();
        
        return $services[$id];
    }

    /**
     * Creates a Package for use with shipments of this
     * carrier.
     *
     * @param array $options the package options
     * 
     * @return Services_Shipping_Package a package with the specified options
     */
    public function createParcel(array $options)
    {
        switch ($options['type']) {
        case 'Letter':
            return new Services_Shipping_DefaultLetter($options);
        case 'Package':
        default:
            return new Services_Shipping_DefaultPackage($options);
        }
    }

    /**
     * Creates a Party for use with this carrier. You can use the DefaultParty
     * object, but this method is more portable.
     *
     * @param array $options options for the party. See
     *        {@link Services_Shipping_Party}.
     * 
     * @return Services_Shipping_Party the created party
     */
    public function createParty(array $options)
    {
        return new Services_Shipping_DefaultParty($options);
    }

    /**
     * Creates an Address for use with this carrier. You can use the DefaultAddress
     * object, but this method is more portable.
     *
     * @param array $options options for the address. See
     *        {@link Services_Shipping_Address}.
     * 
     * @return Services_Shipping_Address the created address
     */
    public function createAddress(array $options)
    {
        return new Services_Shipping_DefaultAddress($options);
    }

    /**
     * Creates a Shipment for use with this carrier. You can use the DefaultShipment
     * object, but this method is more portable.
     *
     * @param array $options options for the shipment. See
     *        {@link Services_Shipping_Shipment}.
     * 
     * @return Services_Shipping_Shipment the created shipment
     */
    public function createShipment(array $options)
    {
        return new Services_Shipping_DefaultShipment($options);
    }
}
?>