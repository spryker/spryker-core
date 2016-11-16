<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payone\Business\Api\Adapter\Http;

use Spryker\Zed\Payone\Business\Api\Adapter\AdapterInterface;
use Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer;
use Spryker\Zed\Payone\Business\Exception\TimeoutException;

abstract class AbstractHttpAdapter implements AdapterInterface
{

    const DEFAULT_TIMEOUT = 45;

    /**
     * @var int
     */
    protected $timeout = self::DEFAULT_TIMEOUT;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $params = [];

    /**
     * @var string
     */
    protected $rawResponse;

    /**
     * @param string $paymentGatewayUrl
     */
    public function __construct($paymentGatewayUrl)
    {
        $this->url = $paymentGatewayUrl;
    }

    /**
     * @param int $timeout
     *
     * @return void
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    public function sendRawRequest(array $params)
    {
        $rawResponse = $this->performRequest($params);
        $result = $this->parseResponse($rawResponse);

        return $result;
    }

    /**
     * @param \Spryker\Zed\Payone\Business\Api\Request\Container\AbstractRequestContainer $container
     *
     * @return array
     */
    public function sendRequest(AbstractRequestContainer $container)
    {
        try {
            return $this->sendRawRequest($container->toArray());
        } catch (TimeoutException $e) {
            $fakeArray = [
                'status' => 'TIMEOUT',
            ];

            return $fakeArray;
        }
    }

    /**
     * @param array $params
     *
     * @return array
     */
    abstract protected function performRequest(array $params);

    /**
     * @param array $params
     *
     * @return array
     */
    protected function generateUrlArray(array $params)
    {
        $urlRequest = $this->getUrl() . '?' . http_build_query($params, null, '&');
        $urlArray = parse_url($urlRequest);

        return $urlArray;
    }

    /**
     * @param array $responseRaw
     *
     * @return array
     */
    protected function parseResponse(array $responseRaw = [])
    {
        $result = [];

        if (count($responseRaw) === 0) {
            return $result;
        }

        foreach ($responseRaw as $key => $line) {
            $pos = strpos($line, '=');

            if ($pos === false) {
                if (strlen($line) > 0) {
                    $result[$key] = $line;
                }
                continue;
            }

            $lineArray = explode('=', $line);
            $resultKey = array_shift($lineArray);
            $result[$resultKey] = implode('=', $lineArray);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param array $params
     *
     * @return void
     */
    public function setParams(array $params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return string
     */
    public function getRawResponse()
    {
        return $this->rawResponse;
    }

    /**
     * @param string $rawResponse
     *
     * @return void
     */
    protected function setRawResponse($rawResponse)
    {
        $this->rawResponse = $rawResponse;
    }

}
