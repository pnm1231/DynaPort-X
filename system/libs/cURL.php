<?php

/**
 * DynaPort X.
 *
 * A simple yet powerful PHP framework for rapid application development.
 *
 * Licensed under BSD license
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright  Copyright (c) 2012-2013 DynamicCodes.com (http://www.dynamiccodes.com/dynaportx)
 * @license    http://www.dynamiccodes.com/dynaportx/license   BSD License
 *
 * @link       https://github.com/pnm1231/DynaPort-X/wiki
 * @since      File available since Release 0.2.0
 */

/**
 * cURL Class.
 *
 * The cURL class which handles all the cURL requests.
 *
 * @category    Libraries
 *
 * @author      Prasad Nayanajith
 *
 * @link        https://github.com/pnm1231/DynaPort-X/wiki/cURL-library
 */
class cURL
{
    /**
     * cURL handler.
     *
     * @var \curl
     */
    private $ch;

    /**
     * cURL error.
     *
     * @var string
     */
    public $error;

    /**
     * cURL error (static).
     *
     * @var string
     */
    private static $errorStatic;

    public function __construct()
    {
        // Check if cURL is installed
        if (!function_exists('curl_init')) {
            new DPxError('Sorry cURL is not installed!');
        }

        // Create a new cURL resource handle
        $this->ch = curl_init();
        $this->setReturnType();
        $this->set(CURLOPT_FAILONERROR, true);
    }

    /**
     * Set the URL.
     *
     * @param string $url URL to access
     */
    public function setUrl($url)
    {
        $url = filter_var($url, FILTER_SANITIZE_URL);
        if (!empty($url)) {
            curl_setopt($this->ch, CURLOPT_URL, $url);
        } else {
            new DPxError('Please provide a valid URL.');
        }
    }

    /**
     * Set the HTTP method to POST.
     *
     * @param string $body POST fields
     */
    public function setHttpPost($body)
    {
        curl_setopt($this->ch, CURLOPT_POST, true);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
    }

    /**
     * Set the HTTP method to PUT.
     *
     * @param string $body PUT fields
     */
    public function setHttpPut($body)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
    }

    /**
     * Set the HTTP method to DELETE.
     *
     * @param string $body DELETE fields
     */
    public function setHttpDelete($body)
    {
        curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $body);
    }

    /**
     * Set the Referring URI.
     *
     * @param string $referer Referring URI
     */
    public function setReferer($referer)
    {
        curl_setopt($this->ch, CURLOPT_REFERER, $referer);
    }

    /**
     * Set a custom User Agent.
     *
     * @param string $userAgent User agent name
     */
    public function setUserAgent($userAgent)
    {
        curl_setopt($this->ch, CURLOPT_USERAGENT, $userAgent);
    }

    /**
     * Include header in the result?
     *
     * @param bool $header true/false
     */
    public function setInclHeader($header = 0)
    {
        curl_setopt($this->ch, CURLOPT_HEADER, $header);
    }

    /**
     * Should cURL return or print out the data?
     *
     * @param bool $return true=return/false=print
     */
    public function setReturnType($return = 1)
    {
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, $return);
    }

    /**
     * Timeout of the cURL request.
     *
     * @param int $timeout Timeout in seconds
     */
    public function setTimeout($timeout)
    {
        if (is_numeric($timeout)) {
            curl_setopt($this->ch, CURLOPT_TIMEOUT, $timeout);
        } else {
            new DPxError('Timeout should be an integer.');
        }
    }

    /**
     * Should the SSL certificates be Verified?
     *
     * @param bool $ssl_verify true/false
     */
    public function setSslVerify($ssl_verify = 1)
    {
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, $ssl_verify);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, $ssl_verify);
    }

    /**
     * Set a cURL option.
     *
     * @param string $key   option
     * @param string $value value
     */
    public function set($key, $value)
    {
        curl_setopt($this->ch, $key, $value);
    }

    /**
     * Output (default: return) the result.
     *
     * @return mixed The result
     */
    public function output()
    {
        // Download the given URL, and return output
        $output = curl_exec($this->ch);

        // Update the error variable if there is one
        $this->error = curl_error($this->ch);

        // Close the cURL resource, and free system resources
        curl_close($this->ch);

        return $output;
    }

    /**
     * Simple GET request.
     *
     * @param string $url        Target URL
     * @param array  $parameters Parameters of GET
     *
     * @return mixed
     */
    public static function get($url, $parameters = [])
    {
        if (count($parameters) > 0) {
            $params = [];
            foreach ($parameters as $k=>$v) {
                $params[] = $k.'='.$v;
            }
            $url = rtrim($url, '?').'?'.implode('&', $params);
        }

        return self::simpleRequest('get', $url);
    }

    /**
     * Simple POST request.
     *
     * @param string $url        Target URL
     * @param array  $parameters Parameters of POST
     *
     * @return mixed
     */
    public static function post($url, $parameters = [])
    {
        return self::simpleRequest('post', $url, $parameters);
    }

    /**
     * Simple PUT request.
     *
     * @param string $url        Target URL
     * @param array  $parameters Parameters of PUT
     *
     * @return mixed
     */
    public static function put($url, $parameters = [])
    {
        return self::simpleRequest('put', $url, $parameters);
    }

    /**
     * Simple DELETE request.
     *
     * @param string $url        Target URL
     * @param array  $parameters Parameters of DELETE
     *
     * @return mixed
     */
    public static function delete($url, $parameters = [])
    {
        return self::simpleRequest('delete', $url, $parameters);
    }

    /**
     * Get the error.
     *
     * @return string The error message
     */
    public static function error()
    {
        return self::$errorStatic;
    }

    /**
     * Send a simple request.
     *
     * @param string $type       Request type
     * @param string $url        Target URL
     * @param array  $parameters Parameters to send
     *
     * @return mixed
     */
    private static function simpleRequest($type, $url, $parameters = [])
    {
        $curl = new self();

        $curl->setUrl($url);

        if ($type == 'post') {
            $curl->setHttpPost($parameters);
        } elseif ($type == 'put') {
            $curl->setHttpPut($parameters);
        } elseif ($type == 'put') {
            $curl->setHttpDelete($parameters);
        }

        if ($_SERVER['ENVIRONMENT'] == 'sandbox') {
            $curl->setSslVerify(0);
        }

        $output = $curl->output();

        self::$errorStatic = $curl->error;

        return $output;
    }
}
