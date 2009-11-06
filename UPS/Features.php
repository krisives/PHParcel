<?php

/**
 * Classes for parcel and shipment features for the UPS carrier.
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

require_once 'Services/Shipping/UPS.php';

/**
 * Adds the "InsuredValue" feature for a parcel.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Package
 */
class Services_Shipping_Driver_UPS_InsuredValue
    implements Services_Shipping_ParcelFeature
{
    /**
     * Gets the name of this parcel feature. This is "InsuredValue".
     *
     * @return string the name
     */
    public function getKey()
    {
        return 'InsuredValue';
    }
    
    /**
     * Transforms some data used by the UPS carrier.
     *
     * @param mixed $conf  the "InsuredValue" are of the parcel features
     * @param mixed &$data data to transform
     * 
     * @return void
     */
    public function apply($conf, &$data)
    {
        $data['PackageServiceOptions']['InsuredValue'] = array(
            'MonetaryValue' => $conf['value'],
                'Type' => array(
                'Code' => empty($conf['type']) ? '01' : $conf['type']
            )
        );
    }
}

/**
 * Adds the "AdditionalHandling" feature for a parcel.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Package
 */
class Services_Shipping_Driver_UPS_AdditionalHandling
    implements Services_Shipping_ParcelFeature
{
    /**
     * Gets the name of this parcel feature. This is "AdditionalHandling".
     *
     * @return string the name
     */
    public function getKey()
    {
        return 'AdditionalHandling';
    }
    
    /**
     * Transforms some data used by the UPS carrier.
     *
     * @param mixed $conf  the "AdditionalHandling" are of the parcel features
     * @param mixed &$data data to transform
     * 
     * @return void
     */
    public function apply($conf, &$data)
    {
        $data['AdditionalHandling'] = $conf ? 1 : 0;
    }
}

/**
 * Adds the "DeliveryConfirmation" feature for a parcel.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Package
 */
class Services_Shipping_Driver_UPS_DeliveryConfirmation
    implements Services_Shipping_ParcelFeature
{
    /**
     * Gets the name of this parcel feature. This is "AdditionalHandling".
     *
     * @return string the name
     */
    public function getKey()
    {
        return 'Signature';
    }
    
    /**
     * Transforms some data used by the UPS carrier.
     *
     * @param mixed $conf  the "AdditionalHandling" are of the parcel features
     * @param mixed &$data data to transform
     * 
     * @return void
     */
    public function apply($conf, &$data)
    {
        switch($conf){
        case 'required':
            $value = '1';
            break;
        case 'adult':
            $value = '2';
            break;
        case 'none':
            return;
        default:
            throw new Exception();
        }
        
        $data['PackageServiceOptions']['DeliveryConfirmation']['DCISType'] = $value;
    }
}

/**
 * Adds the "SaturdayDelivery" feature for a shipment.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage UPS
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Package
 */
class Services_Shipping_Driver_UPS_SaturdayDelivery
    implements Services_Shipping_ShipmentFeature
{
    /**
     * Gets the name of this parcel feature. This is "SaturdayDelivery".
     *
     * @return string the name
     */
    public function getKey()
    {
        return 'SaturdayDelivery';
    }

    /**
     * Transforms some data used by the UPS carrier.
     *
     * @param mixed $conf  the "SaturdayDelivery" are of the parcel features
     * @param mixed &$data data to transform
     * 
     * @return void
     */
    public function apply($conf, &$data)
    {
        $data['ShipmentServiceOptions']['SaturdayDelivery'] = array();
    }
}
?>