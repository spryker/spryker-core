<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\DynamicEntityBackendApi\Logger;

use Exception;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Psr\Log\LoggerInterface;

class DynamicEntityBackendApiLogger implements DynamicEntityBackendApiLoggerInterface
{
    /**
     * @var string
     */
    protected const REQUEST_LOG_PLACEHOLDER = '%s - Request %s [%s] %s - Token %s';

    /**
     * @var string
     */
    protected const ERROR_LOG_PLACEHOLDER = '%s - Exception %s [%s] %s - Token %s %s';

    /**
     * @var string
     */
    protected const PREVIOUS_MESSAGE_PLACEHOLDER = '- Previous Message %s';

    /**
     * @var string
     */
    protected const MAIN_MESSAGE_PLACEHOLDER = '- Main Message ';

    /**
     * @var string
     */
    protected const X_REAL_IP_HEADER = 'x-real-ip';

    /**
     * @var string
     */
    protected const AUTHORIZATION_HEADER = 'authorization';

    /**
     * @var string
     */
    protected const PAYLOAD_CONTEXT = 'payload';

    /**
     * @var string
     */
    protected const TOKEN_IS_NOT_PROVIDED_MESSAGE = 'is not provided';

    /**
     * @var string
     */
    protected const IP_NOT_DETECTED = 'ip-not-detected';

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
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     *
     * @return void
     */
    public function logInfo(GlueRequestTransfer $glueRequestTransfer): void
    {
        if ($this->logger === null) {
            return;
        }

        $message = $this->buildLogMessage($glueRequestTransfer, static::REQUEST_LOG_PLACEHOLDER);

        $this->logger->info($message);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param \Exception $exception
     *
     * @return void
     */
    public function logError(GlueRequestTransfer $glueRequestTransfer, Exception $exception): void
    {
        if ($this->logger === null) {
            return;
        }

        $message = $this->buildLogMessage($glueRequestTransfer, static::ERROR_LOG_PLACEHOLDER, $this->buildErrorMessage($exception));

        $this->logger->error($message, [static::PAYLOAD_CONTEXT => $glueRequestTransfer->getAttributes()]);
    }

    /**
     * @param \Generated\Shared\Transfer\GlueRequestTransfer $glueRequestTransfer
     * @param string $messagePlaceholder
     * @param string|null $message
     *
     * @return string
     */
    protected function buildLogMessage(
        GlueRequestTransfer $glueRequestTransfer,
        string $messagePlaceholder,
        ?string $message = null
    ): string {
        return sprintf(
            $messagePlaceholder,
            date('Y-m-d H:i:s'),
            $glueRequestTransfer->getMeta()[static::X_REAL_IP_HEADER][0] ?? static::IP_NOT_DETECTED,
            $glueRequestTransfer->getResourceOrFail()->getMethod(),
            $glueRequestTransfer->getPath(),
            $glueRequestTransfer->getMeta()[static::AUTHORIZATION_HEADER][0] ?? static::TOKEN_IS_NOT_PROVIDED_MESSAGE,
            $message ?? null,
        );
    }

    /**
     * @param \Exception $exception
     *
     * @return string
     */
    protected function buildErrorMessage(Exception $exception): string
    {
        $message = '';

        if ($exception->getPrevious() instanceof Exception) {
            $message = sprintf(static::PREVIOUS_MESSAGE_PLACEHOLDER, $exception->getPrevious()->getMessage());
        }

        $message .= sprintf(static::MAIN_MESSAGE_PLACEHOLDER, $exception->getMessage());

        return $message;
    }
}
