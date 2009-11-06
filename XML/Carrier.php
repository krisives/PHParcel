<?php

/**
 * Classes that use XML to perform shipping operations.
 * 
 * PHP versions 5.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @copyright  2008 Santiance Corporation
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @version    CVS: $Id:$
 * @link       http://www.santiance.com/open/Services_Shipping
 */

require_once 'Services/Shipping/Carrier.php';

/**
 * A carrier that uses libcurl to communicate over HTTP. SSl, and any
 * other CURL-specific setting, may be configured using the 'curl_options'
 * when creating an instance of this class.
 * 
 * @category   Services
 * @package    Services_Shipping
 * @subpackage XML
 * @author     Kristopher Ives <nullmind@gmail.com>
 * @license    BSD http://www.santiance.com/open/Services_Shipping/license.txt
 * @link       http://www.santiance.com/open/Services_Shipping/Carrier
 */
abstract class Services_Shipping_XML_Carrier extends Services_Shipping_Carrier
{
    /**
     * A mapping of CURL configurations that are set for any communications.
     * 
     * @var array
     */
    public $curl_options = array();

    /**
     * Constructs this XML carrier with the provided options.
     *
     * @param array $options an array keyed by option. See the class
     *        descroption for options.
     */
    public function Services_Shipping_XML_Carrier($options = null)
    {
        if (isset($options)) {
            $this->curl_options = $options['curl_options'];
        }
    }

    /**
     * Transforms the XML document to a string for before being sent.
     * In most cases this will give you
     * the ability to wrap, concatenate, or in some way mutate
     * the rendered XML to include information such as credentials
     * or keys.
     *
     * By default this just returns $node->saveXML()
     *
     * @param DOMNode $node the XML node being sent
     * 
     * @return string the serialize XML data
     */
    protected function prepareRequest(DOMDocument $node)
    {
        return $node->saveXML();
    }
    
    /**
     * Transforms the XML data into an XML document object.
     * 
     * By default this just <code>returns DOMDocument::loadXML($xml)</code>.
     *
     * @param string $xml the XML data string
     * 
     * @return DOMDocument the response XML document
     */
    protected function prepareResponse($xml)
    {
        return DOMDocument::loadXML($xml);
    }

    /**
     * Makes a request to the specified target using an XML
     * node.
     *
     * @param string      $target the target URL
     * @param DOMDocument $node   the XML request
     * 
     * @return DOMDocument the XML response
     */
    protected function request($target, DOMDocument $node)
    {
        if (Services_Shipping::$debugging) {
            $node->formatOutput = true;
        }
         
        $xmlRequest = $this->prepareRequest($node);
         
        if (Services_Shipping::$debugging > 2) {
            echo $xmlRequest;
        }
         
        if (($url = parse_url($target)) === false) {
            throw new Exception("Target URL is invalid: $target");
        }
        
        $headers = array(
            "POST {$url['path']}{$url['query']} HTTP/1.0",
            "Content-type: text/xml; charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-Length: ".strlen($xmlRequest)
        );
         
        $curl = curl_init();
         
        curl_setopt($curl, CURLOPT_URL, $target);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xmlRequest);
         
        foreach ($this->curl_options as $key => $value) {
            if (!curl_setopt($curl, $key, $value)) {
                curl_close();
                throw new Exception("Unable to set CURL option: $key = $value");
            }
        }
         
        $xmlResponse = curl_exec($curl);
         
        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            throw new Exception("Protocol error: $error");
        }
         
        curl_close($curl);
         
        $response = $this->prepareResponse($xmlResponse);
         
        if (!$response) {
            if (Services_Shipping::$debugging) {
                echo $xmlResponse;
            }

            throw new Exception("Unable to parse response XML");
        }
         
        $response->formatOutput = Services_Shipping::$debugging;
         
        if (Services_Shipping::$debugging > 2) {
            echo $response->saveXML();
        }
         
        return $response;
    }
}
?>