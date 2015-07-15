<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Lumberjack\Business\Model\ElasticSearch;

use SprykerFeature\Shared\Library\Config;
use SprykerFeature\Shared\Lumberjack\LumberjackConfig;

class Proxy
{

    const SEARCH_TYPE_SINGLE = 'single';
    const SEARCH_TYPE_MULTI = 'multi';

    protected $config = [
        'timeout' => 180,
        'keepalive' => true,
    ];

    /**
     * @return string
     */
    public function getMapping()
    {
        $url = $this->getBaseUrl() . $this->factory->createSettings()->getIndexName(true) . '/'
            . $this->factory->createSettings()->getMappingUrl();

        $http = new \Zend_Http_Client($url);
        $http->setConfig($this->config);

        return $http->request()->getBody();
    }

    /**
     * @param array $getParams
     * @param array $postData
     * @param string $type
     *
     * @throws \ErrorException
     *
     * @return string
     */
    public function getSearch(array $getParams, array $postData, $type = self::SEARCH_TYPE_SINGLE)
    {
        $url = $this->getBaseUrl() . $this->factory->createSettings()->getIndexName(true)
            . '/' . $this->getSearchUrlByType($type);

        $http = new \Zend_Http_Client($url);
        $http->setConfig($this->config);
        $http->setParameterGet($getParams);

        if (!array_key_exists('request', $postData)) {
            throw new \ErrorException('The JSON search must be posted using the data key "request"!');
        }

        $http->setRawData($postData['request']);

        return str_replace('\\u0000', '', $http->request()->getBody());
    }

    /**
     * @return array
     */
    protected function getElasticSearchConfig()
    {
        $config = Config::get(LumberjackConfig::LUMBERJACK);

        return [
            'host' => $config->elasticsearch->host,
            'port' => $config->elasticsearch->port,
            'index' => $config->elasticsearch->index,
        ];
    }

    /**
     * @param string $type
     *
     * @return string
     */
    protected function getSearchUrlByType($type)
    {
        switch ($type) {
            case self::SEARCH_TYPE_SINGLE:
                return $this->factory->createSettings()->getSearchUrl();
                break;

            case self::SEARCH_TYPE_MULTI:
                return $this->factory->createSettings()->getMultiSearchUrl();
                break;

            default:
                return $this->factory->createSettings()->getSearchUrl();
        }
    }

    /**
     * @return string
     */
    protected function getBaseUrl()
    {
        $config = Config::get(LumberjackConfig::LUMBERJACK);
        $protocol = $config->elasticsearch->protocol;
        $host = $config->elasticsearch->host;
        $port = $config->elasticsearch->port;

        return $protocol . '://' . $host . ':' . $port . '/';
    }

}
