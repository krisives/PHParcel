<?php

/**
 * Classes used with addresses for shipping with the UPS
 * carrier.
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

require_once 'Services/Shipping/XML/Address.php';

/*
 * An address that has been verified by the UPS
 * Address Verification System (AVS).
 */
/*
class Services_Shipping_UPS_Address extends Services_Shipping_XML_Address{
	static $MAP = array(
		'City' => 'string(Address/City)',
		'State' => 'string(Address/StateProvinceCode/text())',
		'PostalCode' => 'concat(string(PostalCodeLowEnd/text()),"-", string(PostalCodeHighEnd/text()))',
		'Rank' => 'string(Rank)',
		'Quality' => 'string(Quality)'
	);
	
	public function Services_Shipping_UPS_Address(DOMNode $node){
		parent::Services_Shipping_XML_Address($node, UPSVerifiedAddress::$MAP);
		
		if($node->nodeName != 'AddressValidationResult'){
			throw new Exception("Expecting a AddressValidationResult, not {$node->nodeName}");
		}
	}
	
	public function getQuality(){
		return $this->get('Quality');
	}
	
	public function getRank(){
		return $this->get('Rank');
	}
}
*/
?>