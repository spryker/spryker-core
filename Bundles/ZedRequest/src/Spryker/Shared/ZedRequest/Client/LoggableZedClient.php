<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\ZedRequest\Client;

use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface;

class LoggableZedClient implements AbstractZedClientInterface
{
    /**
     * @var \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface
     */
    protected $zedClient;

    /**
     * @var \Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface
     */
    protected $zedRequestLogger;

    /**
     * @param \Spryker\Shared\ZedRequest\Client\AbstractZedClientInterface $zedClient
     * @param \Spryker\Shared\ZedRequest\Logger\ZedRequestLoggerInterface $zedRequestLogger
     */
    public function __construct(AbstractZedClientInterface $zedClient, ZedRequestLoggerInterface $zedRequestLogger)
    {
        $this->zedClient = $zedClient;
        $this->zedRequestLogger = $zedRequestLogger;
    }

    /**
     * @param string $url
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface $object
     * @param array|null $requestOptions
     *
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    public function call(string $url, TransferInterface $object, ?array $requestOptions = null)
    {
        $result = $this->zedClient->call($url, $object, $requestOptions);
        $this->zedRequestLogger->log($url, $object->toArray(), $result->toArray());

        return $result;
    }

    /**
     * @param string $name
     * @param mixed $metaTransfer
     *
     * @return $this
     */
    public function addMetaTransfer($name, $metaTransfer)
    {
        $this->zedClient->addMetaTransfer($name, $metaTransfer);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasLastResponse(): bool
    {
        return $this->zedClient->hasLastResponse();
    }

    /**
     * @return \Spryker\Shared\ZedRequest\Client\ResponseInterface
     */
    public function getLastResponse(): ResponseInterface
    {
        return $this->zedClient->getLastResponse();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getInfoStatusMessages(): array
    {
        return $this->zedClient->getInfoStatusMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getErrorStatusMessages(): array
    {
        return $this->zedClient->getErrorStatusMessages();
    }

    /**
     * @return \Generated\Shared\Transfer\MessageTransfer[]
     */
    public function getSuccessStatusMessages(): array
    {
        return $this->zedClient->getSuccessStatusMessages();
    }
}
