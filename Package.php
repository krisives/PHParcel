<?php

/**
 * Classes for package parcels and features.
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

require_once 'Services/Shipping/Parcel.php';

/**
 * A Parcel that may store information about it's mass
 * or volume.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Package
 */
interface Services_Shipping_Package extends Services_Shipping_Parcel
{
    /**
     * Gets the total weight of this package in an expected format,
     * which is assumed to be pounds (lbs).
     * 
     * @return float the weight of the package in pounds (lbs)
     */
    public function getWeight();
    // TODO add $format argument for kilograms, etc. and avoid conversions
    
    /**
     * Gets an array that contains the keys 'width', 'height',
     * and 'length' which describe the size of the package.
     * 
     * @return array|null the dimensions or null if the package doesn't
     *         contain size information.
     */
    public function getDimensions();
}

/**
 * A default implementation of a Package. This just stores the data in
 * public member variables and implements the "get" methods to return
 * that member variable.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Package
 */
class Services_Shipping_DefaultPackage
    extends    Services_Shipping_DefaultParcel
    implements Services_Shipping_Package
{
    /**
     * See {@link Services_Shipping_Package#getWeight()}.
     * 
     * @var float
     */
    protected $weight;
    
    /**
     * See {@link Services_Shipping_Package#getDimensions()}.
     *
     * @var array|null
     */
    protected $dimensions;
    
    /**
     * Constructs this DefaultPackage from the options. See
     * {@link Services_Shipping_Package} for keys/values.
     *
     * @param array $options the option key/values.
     */
    public function __construct(array $options = null)
    {
        parent::__construct($options);
        
        $this->weight     = $options['weight'];
        $this->dimensions = $options['dimensions'];
    }
    
    /**
     * Converts this Package into a string. This describes it's weight
     * and dimensions.
     *
     * @return unknown
     */
    public function __toString()
    {
        return "Package ({$this->getWeight()}lbs) ";
    }
    
    /**
     * Gets this packages weight. See
     * {@link Services_Shipping_Package#getWeight()}.
     *
     * @return float the package weight
     */
    public function getWeight()
    {
        return $this->weight;
    }
    
    /**
     * Gets this packages dimensions as an array with the keys 'width',
     * 'height', and 'length'. See
     * {@link Services_Shipping_Package#getDimensions()}.
     *
     * @return array|null the package dimensions; or null if none are available
     */
    public function getDimensions()
    {
        return $this->dimensions;
    }
}
?>