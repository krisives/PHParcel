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


/**
 * An address used for shipping. This class is very much centered
 * around United States addresses, but international support is
 * planned. Currently "States" are "Providences" when dealing with
 * other regions.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Address
 */
interface Services_Shipping_Address
{
    /**
     * Gets the state of this shipping address. This should be
     * a string of length 2 in upper-case, such as 'OR' for
     * Oregon.
     * 
     * @return string the state code
     */
    public function getState();
    
    /**
     * Gets the name of the city of this shipping address.
     * 
     * @return string the city name
     */
    public function getCity();
    
    /**
     * Gets the country of the this shipping address. This should be
     * a string of length 2 in upper-case, such as 'US' for The United
     * States of America.
     *
     * @return string the state code
     */
    public function getCountry();

    /**
     * Gets the postal code of this shipping address. This should be
     * a string of length 5, which may contain non-digit characters for
     * some locales. This should return an empty string if unknown.
     *
     * @return string the (typically 5-digit) postal code as a string or an
     * empty string if unknown
     */
    public function getPostalCode();

    /**
     * Get the street name of this shipping address. If the shipping
     * address is to be broken into multiple lines, separate them with
     * a newline ("\n") character. If the street address is unknown return
     * an empty string.
     * 
     * @return string the street address line(s) or an empty string if unknown
     */
    public function getStreet();
}

/**
 * A ShippingAddress with storage as member variables.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Address
 */
class Services_Shipping_DefaultAddress
    implements Services_Shipping_Address
{
    /**
     * See {@link Services_Shipping_Address#getState()}.
     * 
     * @var string
     */
    protected $state;
    
    /**
     * See {@link Services_Shipping_Address#getPostalCode()}.
     * 
     * @var string
     */
    protected $postalCode;
    
    /**
     * See {@link Services_Shipping_Address#getCity()}.
     * 
     * @var string
     */
    protected $city;
    
    /**
     * {@link Services_Shipping_Address#getCountry()}.
     * 
     * @var string
     */
    protected $country;
    
    /**
     * See {@link Services_Shipping_Address#getStreet()}. Separated by
     * new lines for each address line.
     * 
     * @var string
     */
    protected $street;
    
    /**
     * Constructs this shipping address from an array of options.
     * 
     * @param array $options the options that may contain keys of the public members
     */
    public function __construct(array $options=null)
    {
        if (!empty($options)) {
            $this->street     = $options['street'];
            $this->postalCode = $options['postalCode'];
            $this->city       = $options['city'];
            $this->state      = $options['state'];
            $this->country    = $options['country'];
        }
    }
    
    /**
     * Gets this address as a string.
     *
     * @return string this object as a string
     */
    public function __toString()
    {
        return "{$this->getStreet()} ("
            .$this->getPostalCode()." "
            .$this->getCity().", "
            .$this->getState().")"
        ;
    }
    
    /**
     * Gets the value from the 'state' member variable. See
     * {@link Services_Shipping_Address#getState()}.
     *
     * @return string the value of the member variable
     */
    public function getState()
    {
        return $this->state;
    }
    
    /**
     * Gets the value from the 'city' member variable. See
     * {@link Services_Shipping_Address#getCity()}.
     *
     * @return string the value of the member variable
     */
    public function getCity()
    {
        return $this->city;
    }
    
    /**
     * Gets the value from the 'country' member variable. See
     * {@link Services_Shipping_Address#getCountry()}.
     *
     * @return string the value of the member variable
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * Gets the value from the 'postalCode' member variable. See
     * {@link Services_Shipping_Address#getPostalCode()}.
     *
     * @return string the value of the member variable
     */
    public function getPostalCode()
    {
        return $this->postalCode;
    }
    
    /**
     * Gets the value from the 'street' member variable. See
     * {@link Services_Shipping_Address#getStreet()}.
     *
     * @return string the value of the member variable
     */
    public function getStreet()
    {
        return $this->street;
    }
}
?>