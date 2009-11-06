<?php

/**
 * A framework for shipping using drivers for carriers. This
 * file doesn't include any carriers and requires a driver.
 * Drivers should be packages named
 * Services_Shipping_Driver_<driver_name> where driver_name
 * can be any valid fragment of a class name, except it
 * must be in all upper case.
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

/* Stuff used from PEAR */
require_once 'PEAR.php';
require_once 'PEAR/Registry.php';

/* Stuff that is provided by this package */
require_once 'Services/Shipping/Exceptions.php';
require_once 'Services/Shipping/Parcel.php';
require_once 'Services/Shipping/Letter.php';
require_once 'Services/Shipping/Package.php';
require_once 'Services/Shipping/Shipment.php';
require_once 'Services/Shipping/Carrier.php';
require_once 'Services/Shipping/Address.php';
require_once 'Services/Shipping/Party.php';
require_once 'Services/Shipping/ShipmentEstimate.php';
require_once 'Services/Shipping/ShipmentReceipt.php';
require_once 'Services/Shipping/ShipmentTracking.php';

/* Stuff that used to exist */
//require_once 'Services/Shipping/ParcelFeature.php';
//require_once 'Services/Shipping/ShipmentFeature.php';
//require_once 'Services/Shipping/ShippingService.php';

/**
 * The entry point class for Services_Shipping. You may enumerate
 * all the available carriers using {@link #getCarriers()} or
 * get a specific carrier using {@link #getCarrier($id)}.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping
 */
abstract class Services_Shipping
{
    /**
     * Level of debugging output that should be provided, which
     * defaults to false, which is disabled. A value of 1 is
     * debugging info before bad stuff happens, like exceptions,
     * errors, and warnings. A value of 2 is more detailed info
     * about transactions, etc.
     *
     * @var int
     */
    public static $debugging = false;
    
    //const DOMESTIC = 1;
    //const INTERNATIONAL = 2;
    //private static $carriers = array();
    
    /**
     * Gets all the carriers that are installed in PEAR. This is
     * done by checking all PEAR packages that are prefixed
     * with Services_Shipping_Driver. "Helper" packages such
     * as Services_Shipping_XML are not drivers, since they
     * are abstract.
     *
     * @return array an array of names of the carriers.
     */
    public static function getCarriers()
    {
        $reg      = new PEAR_Registry();
        $carriers = array();
        $pattern  = '/^Services_Shipping_Driver_(.*)$/i';
        
        foreach ($reg->listPackages() as $package) {
            if (!preg_match($pattern, $package, $matches)) {
                continue;
            }
            
            $name = strtoupper($matches[1]);
            
            if (strlen($name) <= 0) {
                continue;
            }
            
            $carriers[] = $name;
        }
        
        return $carriers;
    }
    
    /**
     * Creates an instance of a carrier with the specified options. This
     * will also include the carrier scripts using include_once. This uses
     * PHP_Registry to find all driver packages, which begin with
     * "Services_Shipping_Driver_".
     *
     * @param string $id      the name of the carrier. This is given by
     *        getCarriers().
     * @param array  $options a set of options for a carrier. See
     *        {@link Services_Shipping_Carrier}.
     * 
     * @return Services_Shipping_Carrier the carrier
     */
    public static function getCarrier($id, $options = null)
    {
    	$id          = strtoupper($id);
        $packageName = "Services_Shipping_Driver_$id";
        $reg         = new PEAR_Registry();
        
        if ($reg->getPackage($packageName) == null) {
            trigger_error("Unknown carrier '$id'", E_USER_WARNING);
            return false;
        }
        
        include_once "Services/Shipping/{$id}.php";
        
        $name = "Services_Shipping_Driver_{$id}";
        
        return new $name($options);
    }
}
?>