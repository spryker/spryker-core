<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ApiKeyAuthorizationConnector\Business\Logger;

use Generated\Shared\Transfer\AuthorizationRequestTransfer;
use Psr\Log\LoggerInterface;

class ApiKeyAuthorizationLogger implements ApiKeyAuthorizationLoggerInterface
{
    /**
     * @var string
     */
    protected const FORMAT_DATE_TIME = 'Y-m-d H:i:s';

    /**
     * @var string
     */
    protected const METHOD = 'method';

    /**
     * @var string
     */
    protected const PATH = 'path';

    /**
     * @var string
     */
    protected const REQUEST_LOG_PLACEHOLDER = '%s - Request [%s] %s - API Key %s';

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    protected ?LoggerInterface $logger;

    /**
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(?LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param string $apiKey
     *
     * @return void
     */
    public function logInfo(AuthorizationRequestTransfer $authorizationRequestTransfer, string $apiKey): void
    {
        if ($this->logger === null) {
            return;
        }

        $message = $this->buildLogMessage($authorizationRequestTransfer, static::REQUEST_LOG_PLACEHOLDER, $apiKey);

        $this->logger->info($message);
    }

    /**
     * @param \Generated\Shared\Transfer\AuthorizationRequestTransfer $authorizationRequestTransfer
     * @param string $messagePlaceholder
     * @param string $apiKey
     *
     * @return string
     */
    protected function buildLogMessage(
        AuthorizationRequestTransfer $authorizationRequestTransfer,
        string $messagePlaceholder,
        string $apiKey
    ): string {
        return sprintf(
            $messagePlaceholder,
            date(static::FORMAT_DATE_TIME),
            $authorizationRequestTransfer->getEntityOrFail()->getData()[static::METHOD],
            $authorizationRequestTransfer->getEntityOrFail()->getData()[static::PATH],
            $apiKey,
        );
    }
}
