<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log;

use Spryker\Glue\Kernel\AbstractBundleConfig;
use Spryker\Shared\Log\LogConstants;

class LogConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getChannelName(): string
    {
        return $this->get(LogConstants::LOGGER_CHANNEL_GLUE, 'Glue');
    }

    /**
     * @api
     *
     * @return array
     */
    public function getSanitizerFieldNames(): array
    {
        return $this->get(LogConstants::LOG_SANITIZE_FIELDS, []);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getSanitizedFieldValue(): string
    {
        return $this->get(LogConstants::LOG_SANITIZED_VALUE, '***');
    }

    /**
     * @api
     *
     * @return string
     */
    public function getLogFilePath(): string
    {
        if ($this->getConfig()->hasKey(LogConstants::LOG_FILE_PATH_GLUE)) {
            return $this->get(LogConstants::LOG_FILE_PATH_GLUE);
        }

        return $this->get(LogConstants::LOG_FILE_PATH);
    }

    /**
     * @api
     *
     * @return int|string
     */
    public function getLogLevel()
    {
        return $this->get(LogConstants::LOG_LEVEL);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getExceptionLogFilePath(): string
    {
        if ($this->getConfig()->hasKey(LogConstants::EXCEPTION_LOG_FILE_PATH_GLUE)) {
            return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH_GLUE);
        }

        return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getQueueName(): string
    {
        return $this->get(LogConstants::LOG_QUEUE_NAME);
    }
}
