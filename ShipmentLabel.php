<?php

/**
 * Classes for describing labels and making label generation
 * easier.
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
 * A label that contains some form of image data, which is typically used for 
 * printing.
 * 
 * @category Services
 * @package  Services_Shipping
 * @author   Kristopher Ives <nullmind@gmail.com>
 * @license  LGPL http://www.gnu.org/licenses/lgpl-3.0.txt
 * @link     http://www.santiance.com/open/Services_Shipping/ShipmentLabel
 */
interface Services_Shipping_ShipmentLabel
{
    /**
     * Gets the image data for this label.
     * 
     * @return string the image data as a binary string
     */
    public function getImageData();
    
    /**
     * Gets the format of the image data for this label.
     * 
     * @return string the image format name in upper-case (example: 'GIF')
     */
    public function getImageFormat();
}
?>