<?php

/**
 * Classes that deal with document, letters, customs, etc.
 * parcels, shipments, and features.
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
 * A parcel that conforms to a carriers expectation of a document 
 * or letter. If it has volume or mass see {@link Services_Shipping_Package}.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Letter
 */
interface Services_Shipping_Letter extends Services_Shipping_Parcel
{
    // For now this just tags something as a Letter
}

/**
 * A Letter that stores data as member variables.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/Letter
 */
class Services_Shipping_DefaultLetter
    extends    Services_Shipping_DefaultParcel
    implements Services_Shipping_Letter
{
    /**
     * Creates this Letter from the specified options. 
     *
     * @param array $options the options (See {@link Services_Shipping_Letter}
     * for key/values)
     */
    public function Services_Shipping_DefaultLetter(array $options = null)
    {
        parent::__construct($options);
        
        // Just a tagging class for now
    }
    
    /**
     * Converts this letter to a string.
     *
     * @return string this object as a string
     */
    public function __toString()
    {
        return "Letter (from {$this->getSender()} to {$this->getRecipient()}";
    }
}
?>