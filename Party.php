<?php

/**
 * Classes about entities and people that use
 * shipping services.
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

require_once 'Services/Shipping/Address.php';

/**
 * An entity that sends or receives shipments. Provides
 * information about the address, name, and phone contact
 * for the party.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Party
 */
interface Services_Shipping_Party
{
    /**
     * Gets the ShippingAddress object of where this party is located.
     * 
     * @return Services_Shipping_ShippingAddress the address of this party
     */
    public function getAddress();
    
    /**
     * Gets the name of the shipping party. This may be used for
     * shipping labels, e-mails, and other shipping related tasks.
     * 
     * @return string the name of the shipping party
     */
    public function getName();
    
    /**
     * Gets the phone number of the party. This field may have different
     * formats.
     * 
     * @return the phone number
     */
    public function getPhoneNumber();
}

/**
 * A default implementation of ShippingParty where data is backed
 * by member variables which are public.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Party
 */
class Services_Shipping_DefaultParty
    implements Services_Shipping_Party
{
    /**
     * The name of this party. See
     * {@link Services_Shipping_Party#getName()}.
     *
     * @var string
     */
    protected $name;
    
     /**
     * The address of this party. See
     * {@link Services_Shipping_Party#getAddress()}.
     *
     * @var Services_Shipping_Address
     */
    protected $address;
    
     /**
     * The phone number of this party. See
     * {@link Services_Shipping_Party#getPhoneNumber()}.
     *
     * @var string
     */
    protected $phoneNumber;
    
    /**
     * Constructs this shipping party from an array of options.
     * The array may contain the 'name', 'address', and 'phoneNumber'
     * keys.
     *
     * @param array $options the options for this shipping party
     */
    public function __construct($options = null)
    {
        if (empty($options)) {
            return;
        }
        
        foreach (array('name') as $key) {
            if (!array_key_exists($key, $options)) {
                throw new InvalidArgumentException("options must include $key");
            }
            
            $this->$key = $options[$key];
        }
        
        foreach (array('address', 'phoneNumber') as $key) {
        	if (array_key_exists($key, $options)) {
        		$this->$key = $options[$key];
        	}
        }
    }
    
    /**
     * Converts this Party into a string. This just contains the
     * name and address.
     * 
     * @return string this as a string
     */
    public function __toString()
    {
        return "'{$this->getName()}' ({$this->getAddress()})";
    }
    
    /**
     * Gets the address of this Party. See
     * {@link #$address}.
     *
     * @return Services_Shipping_Address|null the value of the member
     */
    public function getAddress()
    {
        return $this->address;
    }
    
    /**
     * Gets the name of this Party. See
     * {@link #$name}.
     *
     * @return string the value of the member
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * Gets the phone number of this Party. See
     * {@link #$phoneNumber}.
     *
     * @return string|null the member value
     */
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }
}
?>