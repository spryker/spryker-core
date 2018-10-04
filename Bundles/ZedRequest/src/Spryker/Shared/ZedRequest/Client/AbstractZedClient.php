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
    private $httpClient;

    /**
     * @var \Spryker\Shared\ZedRequest\Client\ResponseInterface|null
     */
    private static $lastResponse = null;

    private const INFO_MESSAGES = 'infoMessages';
    private const ERROR_MESSAGES = 'errorMessages';
    private const SUCCESS_MESSAGES = 'successMessages';

    /**
     * @var \Generated\Shared\Transfer\StatusMessagesTransfer;
     */
    private static $statusMessages = [
        self::INFO_MESSAGES => [],
        self::ERROR_MESSAGES => [],
        self::SUCCESS_MESSAGES => [],
    ];

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface[]|\Closure[]
     */
    private $metaTransfers = [];

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
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface[]
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
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array|int|null $requestOptions (optional) default: null
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call($url, TransferInterface $object, $requestOptions = null)
    {
        self::$lastResponse = null;

        $response = $this->httpClient->request(
            $url,
            $object,
            $this->prepareAndGetMetaTransfers(),
            $requestOptions
        );

        self::$lastResponse = $response;
        $this->collectStatusMessages($response);

        return self::$lastResponse->getTransfer();
    }

    /**
     * @param \Spryker\Shared\ZedRequest\Client\ResponseInterface $response
     *
     * @return void
     */
    private function collectStatusMessages(ResponseInterface $response): void
    {
        foreach ($response->getErrorMessages() as $errorMessage) {
            self::$statusMessages[self::ERROR_MESSAGES][] = $errorMessage;
        }
        foreach ($response->getSuccessMessages() as $successMessage) {
            self::$statusMessages[self::SUCCESS_MESSAGES][] = $successMessage;
        }
        foreach ($response->getInfoMessages() as $infoMessage) {
            self::$statusMessages[self::INFO_MESSAGES][] = $infoMessage;
        }
    }

    /**
     * @return bool
     */
    public function hasLastResponse()
    {
        return self::$lastResponse !== null;
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

        return self::$lastResponse;
    }

    /**
     * @return \Generated\Shared\Transfer\StatusMessagesTransfer;
     */
    public function getInfoStatusMessages(): array
    {
        return self::$statusMessages[self::INFO_MESSAGES];
    }

    /**
     * @return \Generated\Shared\Transfer\StatusMessagesTransfer;
     */
    public function getErrorStatusMessages(): array
    {
        return self::$statusMessages[self::ERROR_MESSAGES];
    }

    /**
     * @return \Generated\Shared\Transfer\StatusMessagesTransfer;
     */
    public function getSuccessStatusMessages(): array
    {
        return self::$statusMessages[self::SUCCESS_MESSAGES];
    }
}
