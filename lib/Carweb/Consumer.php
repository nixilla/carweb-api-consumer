<?php

namespace Carweb;

use Buzz\Message\RequestInterface;
use Buzz\Message\Response;
use Carweb\Cache\CacheInterface;
use Carweb\Converter\ConverterInterface;
use Carweb\Converter\DefaultConverter;
use Carweb\Exception\ApiException;

class Consumer
{
    /**
     * Carweb API version, this library is build against
     */
    const API_VERSION = '0.31.1';

    /**
     * API path on the endpoint
     */
    const API_PATH = 'CarweBVrrB2Bproxy/carwebVrrWebService.asmx';

    /**
     * @var array
     */
    protected $api_endpoints = array(
        'http://www1.carwebuk.com',
        'http://www3.carwebuk.com',
        'http://www2.carwebuk.com',
        'http://www.cwsecondary.net'
    );

    /**
     * @var \Buzz\Browser
     */
    protected $client;

    /**
     * @var string
     */
    protected $strUserName;

    /**
     * @var string
     */
    protected $strPassword;

    /**
     * @var string
     */
    protected $strKey1;

    /**
     * @var null|\Carweb\Cache\CacheInterface
     */
    private $cache;

    /**
     * @var array
     */
    protected $converters = array();

    /**
     * Constructor
     *
     * @param $client
     * @param $strUserName
     * @param $strPassword
     * @param $strKey1
     * @param null|\Carweb\Cache\CacheInterface $cache
     */
    public function __construct($client, $strUserName, $strPassword, $strKey1, CacheInterface $cache = null)
    {
        $this->client = $client;
        $this->strUserName = $strUserName;
        $this->strPassword = $strPassword;
        $this->strKey1 = $strKey1;
        $this->cache = $cache;
    }

    /**
     * Proxy method for strB2BGetVehicleByVRM
     *
     * @param string $vrm
     * @param string $strClientRef
     * @param string $strClientDescription
     * @return mixed|void
     */
    public function findByVRM($vrm, $strClientRef = 'default client', $strClientDescription = 'Carweb PHP Library')
    {
        $vrm = strtoupper(preg_replace('/\s+/', '', $vrm));
        $api_method = 'strB2BGetVehicleByVRM';

        $cache_key = sprintf('%s.%s', $api_method, $vrm);

        $converter = $this->getConverter($api_method);

        if($this->isCached($cache_key))
        {
            $content = $this->getCached($cache_key);
            return $converter->convert($content);
        }

        $input = array(
            'strUserName' => $this->strUserName,
            'strPassword' => $this->strPassword,
            'strKey1' => $this->strKey1,
            'strVersion' => self::API_VERSION,
            'strVRM' => $vrm,
            'strClientRef' => $strClientRef,
            'strClientDescription' => $strClientDescription
        );

        $content = $this->call($api_method, RequestInterface::METHOD_GET, $input);

        $this->setCached($cache_key, $content);

        return $converter->convert($content);
    }

    /**
     * Proxy method for strB2BGetVehicleByVRM
     *
     * @param string $vin
     * @param string $strClientRef
     * @param string $strClientDescription
     * @return mixed|void
     */
    public function findByVIN($vin, $strClientRef = 'default client', $strClientDescription = 'Carweb PHP Library')
    {
        $vin = strtoupper(preg_replace('/\s+/', '', $vin));
        $api_method = 'strB2BGetVehicleByVIN';

        $cache_key = sprintf('%s.%s', $api_method, $vin);

        $converter = $this->getConverter($api_method);

        if($this->isCached($cache_key))
        {
            $content = $this->getCached($cache_key);
            return $converter->convert($content);
        }

        $input = array(
            'strUserName' => $this->strUserName,
            'strPassword' => $this->strPassword,
            'strKey1' => $this->strKey1,
            'strVersion' => self::API_VERSION,
            'strVIN' => $vin,
            'strClientRef' => $strClientRef,
            'strClientDescription' => $strClientDescription
        );

        $content = $this->call($api_method, RequestInterface::METHOD_GET, $input);

        $this->setCached($cache_key, $content);

        return $converter->convert($content);
    }

    public function call($api_method, $http_method = RequestInterface::METHOD_GET, array $query_string = array(), $headers = array(), $content = '')
    {
        $url = sprintf('%s/%s/%s?%s', $this->api_endpoints[array_rand($this->api_endpoints)], self::API_PATH, $api_method, http_build_query($query_string));

        $response = $this->client->call($url, $http_method, $headers, $content);

        if($response->isSuccessful())
        {
            $this->hasErrors($response->getContent());
            return $response->getContent();
        }
        else
        {
            return $this->handleException($response);
        }
    }

    /**
     * Gets converted obj for given API method
     *
     * @param $api_method
     * @return \Carweb\ConverterInterface
     */
    public function getConverter($api_method)
    {
        if(isset($this->converters[$api_method]))
            return $this->converters[$api_method];
        else
            return new DefaultConverter();
    }

    /**
     * Sets converter object for given API method
     *
     * @param $api_method
     * @param ConverterInterface $converter
     * @throws \InvalidArgumentException
     */
    public function setConverter($api_method, $converter)
    {
        if( ! $converter instanceof ConverterInterface)
            throw new \InvalidArgumentException('$converter must be instance of ConverterInterface');

        $this->converters[$api_method] = $converter;
    }

    /**
     * @param Response $response
     * @throws \Exception
     */
    protected function handleException(Response $response)
    {
        throw new \Exception($response->getContent(), $response->getStatusCode());
    }

    protected function hasErrors($xml_string)
    {
        $doc = new \DOMDocument();
        $doc->loadXML($xml_string);

        $xpath = new \DOMXPath($doc);

        $query = '/VRRError/DataArea/Error/Details';

        $entries = $xpath->query($query);

        if($entries->length)
        {
            $error = array();
            foreach($entries as $entry)
                foreach($entry->childNodes as $node)
                    if($node->nodeName != '#text')
                        $error[$node->nodeName] = $node->nodeValue;

            throw new ApiException($error['ErrorDescription'],$error['ErrorCode']);
        }

        return false;
    }

    /**
     * Cache proxy
     *
     * @param $key
     * @return bool
     */
    protected function isCached($key)
    {
        if($this->cache)
            return $this->cache->has($key);
        else
            return false;
    }

    /**
     * Cache proxy
     *
     * @param $key
     * @return mixed
     */
    protected function getCached($key)
    {
        return $this->cache ? $this->cache->get($key) : null;
    }

    /**
     * Cache proxy
     *
     * @param $key
     * @param $value
     * @return mixed
     */
    protected function setCached($key, $value)
    {
        if($this->cache)
            return $this->cache->save($key, $value);
        else
            return false;
    }
}