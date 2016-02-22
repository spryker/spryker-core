<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Transfer\TransferInterface;

abstract class AbstractZedClient implements AbstractZedClientInterface
{

    /**
     * @var \Spryker\Shared\ZedRequest\Client\HttpClientInterface
     */
    private $httpClient = null;

    /**
     * @var \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    private static $lastResponse = null;

    /**
     * @var \Spryker\Shared\Transfer\TransferInterface[]|\Closure[]
     */
    private $metaTransfers = [

    ];

    /**
     * @param \Spryker\Shared\ZedRequest\Client\HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param string $name
     * @param mixed $metaTransfer
     *
     * @return $this
     */
    public function addMetaTransfer($name, $metaTransfer)
    {
        $this->metaTransfers[$name] = $metaTransfer;

        return $this;
    }

    /**
     * @return \Spryker\Shared\Transfer\TransferInterface[]
     */
    private function prepareAndGetMetaTransfers()
    {
        foreach ($this->metaTransfers as $name => $transfer) {
            if (is_object($transfer) && method_exists($transfer, '__invoke')) {
                $this->metaTransfers[$name] = $transfer();
            }
        }

        return $this->metaTransfers;
    }

    /**
     * @param string $url
     * @param \Spryker\Shared\Transfer\TransferInterface $object
     * @param int|null $timeoutInSeconds (optional) default: null
     * @param bool $isBackgroundRequest (optional) default: false
     *
     * @return \Spryker\Shared\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $timeoutInSeconds = null, $isBackgroundRequest = false)
    {
        self::$lastResponse = null;
        self::$lastResponse = $this->httpClient->request(
            $url,
            $object,
            $this->prepareAndGetMetaTransfers(),
            $timeoutInSeconds,
            $isBackgroundRequest
        );

        return self::$lastResponse->getTransfer();
    }

    /**
     * @throws \BadMethodCallException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function getLastResponse()
    {
        if (self::$lastResponse === null) {
            throw new \BadMethodCallException('There is no response received from zed.');
        }

        return self::$lastResponse;
    }

}
