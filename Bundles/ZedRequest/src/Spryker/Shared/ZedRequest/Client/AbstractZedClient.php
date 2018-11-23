<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use BadMethodCallException;
use Spryker\Shared\Kernel\Transfer\TransferInterface;

abstract class AbstractZedClient implements AbstractZedClientInterface
{
    /**
     * @var \Spryker\Shared\ZedRequest\Client\HttpClientInterface
     */
    protected $httpClient;

    /**
     * @var \Spryker\Shared\ZedRequest\Client\ResponseInterface|null
     */
    protected static $lastResponse;

    protected const INFO_MESSAGES = 'infoMessages';
    protected const ERROR_MESSAGES = 'errorMessages';
    protected const SUCCESS_MESSAGES = 'successMessages';

    /**
     * @var array
     */
    protected static $statusMessages = [
        self::INFO_MESSAGES => [],
        self::ERROR_MESSAGES => [],
        self::SUCCESS_MESSAGES => [],
    ];

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface[]|\Closure[]
     */
    protected $metaTransfers = [];

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
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array|int|null $requestOptions (optional) default: null
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null)
    {
        static::$lastResponse = null;

        $response = $this->httpClient->request(
            $url,
            $object,
            $this->prepareAndGetMetaTransfers(),
            $requestOptions
        );

        static::$lastResponse = $response;
        $this->collectStatusMessages($response);

        return static::$lastResponse->getTransfer();
    }

    /**
     * @return bool
     */
    public function hasLastResponse()
    {
        return static::$lastResponse !== null;
    }

    /**
     * @throws \BadMethodCallException
     *
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function getLastResponse()
    {
        if (!$this->hasLastResponse()) {
            throw new BadMethodCallException('There is no response received from zed.');
        }

        return static::$lastResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getInfoStatusMessages(): array
    {
        return static::$statusMessages[static::INFO_MESSAGES];
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getErrorStatusMessages(): array
    {
        return static::$statusMessages[static::ERROR_MESSAGES];
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getSuccessStatusMessages(): array
    {
        return static::$statusMessages[static::SUCCESS_MESSAGES];
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface[]
     */
    protected function prepareAndGetMetaTransfers()
    {
        foreach ($this->metaTransfers as $name => $transfer) {
            if (is_object($transfer) && method_exists($transfer, '__invoke')) {
                $this->metaTransfers[$name] = $transfer();
            }
        }

        return $this->metaTransfers;
    }

    /**
     * @param \Spryker\Shared\ZedRequest\Client\ResponseInterface $response
     *
     * @return void
     */
    protected function collectStatusMessages(ResponseInterface $response): void
    {
        foreach ($response->getErrorMessages() as $errorMessage) {
            static::$statusMessages[static::ERROR_MESSAGES][] = $errorMessage;
        }
        foreach ($response->getSuccessMessages() as $successMessage) {
            static::$statusMessages[static::SUCCESS_MESSAGES][] = $successMessage;
        }
        foreach ($response->getInfoMessages() as $infoMessage) {
            static::$statusMessages[static::INFO_MESSAGES][] = $infoMessage;
        }
    }
}
