<?php

/**
 * Classes that describe the contents of a shipment and
 * the features of those fragments.
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
 * Describes a piece of a Shipment. This doesn't
 * include any information about how the parcel is being
 * delivered. This separation allows the components of
 * a shipment to be described before a shipment is created.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Parcel
 */
interface Services_Shipping_Parcel
{
    /**
     * Gets a description of this parcel.
     * 
     * @return string the description
     */
    public function getDescription();
    
    /**
     * Gets the features attached to this Parcel.
     * 
     * @return array features attached to this parcel.
     */
    public function getFeatures();
}

/**
 * A handler for attachments to a Parcel.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Parcel
 */
interface Services_Shipping_ParcelFeature
{
    /**
     * Gets the unique identifier for the parcel feature.
     * 
     * @return string the unqiue identifier
     */
    public function getKey();
    
    /**
     * Applies the feature by transforming the data.
     *
     * @param mixed $config the data specified in the features of the parcel
     * @param mixed &$data  data to modify (carrier specific)
     * 
     * @return void
     */
    public function apply($config, &$data);
}

/**
 * A default implementation of a Parcel that stores the
 * description and features as member variables. The features
 * are stored as an array.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Parcel
 */
abstract class Services_Shipping_DefaultParcel
    implements Services_Shipping_Parcel
{
    /**
     * Gets this parcels description. See
     * {@link Services_Shipping_Parcel#getDescription()}.
     *
     * @var string
     */
    protected $description;
    
    /**
     * Gets this parcels features as an array. See
     * {@link Services_Shipping_Parcel#getFeatures()}.
     *
     * @var array|null
     */
    protected $features;
    
    /**
     * Constructs this parcel with the provided options. The
     * 'description' and 'features' keys are optional and will
     * be assigned to their respective member variables.
     *
     * @param array $options an optional array of configuration keys
     *        with configuration settings for values.
     */
    public function __construct($options = null)
    {
        $this->description = $options['description'];
        $this->features    = $options['features'];
    }
    
    /**
     * Gets this parcels description. See
     * {@link Services_Shipping_DefaultParcel#$description}.
     *
     * @return string|null the value of the member variable
     */
    public function getDescription()
    {
        return $this->description;
    }
    
    /**
     * Gets this parcels features array. See
     * {@link Services_Shipping_DefaultParcel#$features}.
     *
     * @return array|null the value of the member variable
     */
    public function getFeatures()
    {
        return $this->features;
    }
}
?>