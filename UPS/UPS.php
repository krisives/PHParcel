<?php

/**
 * A package for shipping services with the UPS carrier.
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

require_once 'PEAR.php';

require_once 'Services/Shipping.php';
require_once 'Services/Shipping/XML.php';

require_once 'Services/Shipping/UPS/Address.php';
require_once 'Services/Shipping/UPS/Party.php';
require_once 'Services/Shipping/UPS/Parcel.php';
require_once 'Services/Shipping/UPS/Package.php';
require_once 'Services/Shipping/UPS/Letter.php';
require_once 'Services/Shipping/UPS/Features.php';
require_once 'Services/Shipping/UPS/ShipmentEstimate.php';
require_once 'Services/Shipping/UPS/ShipmentLabel.php';
require_once 'Services/Shipping/UPS/ShipmentReceipt.php';
require_once 'Services/Shipping/UPS/ShipmentTracking.php';
require_once 'Services/Shipping/UPS/Shipment.php';
require_once 'Services/Shipping/UPS/Carrier.php';

?>