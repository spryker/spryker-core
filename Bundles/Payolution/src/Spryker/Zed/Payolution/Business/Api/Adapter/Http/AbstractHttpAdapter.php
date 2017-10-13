<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Payolution\Business\Api\Adapter\Http;

use Spryker\Zed\Payolution\Business\Api\Adapter\AdapterInterface;

abstract class AbstractHttpAdapter implements AdapterInterface
{
    /**
     * @var static string[]
     */
    public static $requestContentTypes = [
        'FORM' => 'application/x-www-form-urlencoded;charset=UTF-8',
        'XML' => 'text/xml;charset=UTF-8',
    ];

    /**
     * @var string
     */
    protected $gatewayUrl;

    /**
     * @var string
     */
    protected $contentType;

    /**
     * @param string $gatewayUrl
     * @param string $contentType
     */
    public function __construct($gatewayUrl, $contentType)
    {
        $this->gatewayUrl = $gatewayUrl;
        $this->contentType = $contentType;
    }

    /**
     * @param array|string $data
     *
     * @return string
     */
    public function sendRequest($data)
    {
        $request = $this->buildRequest();

        $options = [];
        $options = $this->addPayload($data, $options);

        return $this->send($request, $options);
    }

    /**
     * @param array|string $data
     * @param string $user
     * @param string $password
     *
     * @return string
     */
    public function sendAuthorizedRequest($data, $user, $password)
    {
        $request = $this->buildRequest();
        $options = $this->authorizeRequest($user, $password);
        $options = $this->addPayload($data, $options);

        return $this->send($request, $options);
    }

    /**
     * @return \Psr\Http\Message\RequestInterface
     */
    abstract protected function buildRequest();

    /**
     * @param string $user
     * @param string $password
     *
     * @return array
     */
    abstract protected function authorizeRequest($user, $password);

    /**
     * @param \Psr\Http\Message\RequestInterface $request
     * @param array $options
     *
     * @throws \Spryker\Zed\Payolution\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    abstract protected function send($request, array $options = []);

    /**
     * @param array|string $data
     * @param array $options
     *
     * @return array
     */
    protected function addPayload($data, array $options)
    {
        if (is_array($data)) {
            $options['form_params'] = $data;
        } else {
            $options['body'] = $data;
        }

        return $options;
    }
}
