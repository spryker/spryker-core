<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Ratepay\Business\Api\Adapter\Http;

use Spryker\Zed\Ratepay\Business\Api\Adapter\AdapterInterface;

abstract class AbstractHttpAdapter implements AdapterInterface
{
    /**
     * @var string
     */
    protected $gatewayUrl;

    /**
     * @param string $gatewayUrl
     */
    public function __construct($gatewayUrl)
    {
        $this->gatewayUrl = $gatewayUrl;
    }

    /**
     * @param string $data
     *
     * @return string
     */
    public function sendRequest($data)
    {
        $request = $this->buildRequest($data);

        return $this->send($request);
    }

    /**
     * @param string $data
     *
     * @return object
     */
    abstract protected function buildRequest($data);

    /**
     * @param object $request
     *
     * @throws \Spryker\Zed\Ratepay\Business\Exception\ApiHttpRequestException
     *
     * @return string
     */
    abstract protected function send($request);
}
