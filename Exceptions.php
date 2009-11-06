<?php

/**
 * Exceptions that are unique to shipment related
 * operations.
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


require_once 'PEAR/Exception.php';

/**
 * Thrown when something wrong occurs during a shipping
 * operation.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Exception
 */
class Services_Shipping_Exception extends PEAR_Exception
{
    // Nothing yet
}

/**
 * Thrown is a Shipment cannot be found by a carrier. This is likely
 * to occur if a tracking identifier is invalid.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Exception
 */
class Services_Shipping_ShipmentNotFoundException extends Services_Shipping_Exception
{
    // Nothing yet
}
?>