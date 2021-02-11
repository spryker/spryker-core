<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log;

use Spryker\Shared\Log\LogConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class LogConfig extends AbstractBundleConfig
{
    /**
     * @api
     *
     * @return string
     */
    public function getChannelName()
    {
        return $this->get(LogConstants::LOGGER_CHANNEL_YVES, 'Yves');
    }

    /**
     * @api
     *
     * @return mixed
     */
    public function getSanitizerFieldNames()
    {
        return $this->get(LogConstants::LOG_SANITIZE_FIELDS, []);
    }

    /**
     * @api
     *
     * @return mixed
     */
    public function getSanitizedFieldValue()
    {
        return $this->get(LogConstants::LOG_SANITIZED_VALUE, '***');
    }

    /**
     * Specification:
     * - Defines the log destination path, e.g 'php://stderr' or '/data/log/Yves/application.log'.
     *
     * @api
     *
     * @return resource|string
     */
    public function getLogDestinationPath()
    {
        return $this->getLogFilePath();
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Yves\Log\LogConfig::getLogDestinationPath()} instead.
     *
     * @return string
     */
    public function getLogFilePath()
    {
        if ($this->getConfig()->hasKey(LogConstants::LOG_FILE_PATH_YVES)) {
            return $this->get(LogConstants::LOG_FILE_PATH_YVES);
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
     * Specification:
     * - Defines the log destination path, e.g 'php://stderr' or '/data/log/Yves/exception.log'.
     *
     * @api
     *
     * @return resource|string
     */
    public function getExceptionLogDestinationPath()
    {
        return $this->getExceptionLogFilePath();
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Yves\Log\LogConfig::getExceptionLogDestination()} instead.
     *
     * @return string
     */
    public function getExceptionLogFilePath()
    {
        if ($this->getConfig()->hasKey(LogConstants::EXCEPTION_LOG_FILE_PATH_YVES)) {
            return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH_YVES);
        }

        return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH);
    }

    /**
     * @api
     *
     * @return string
     */
    public function getQueueName()
    {
        return $this->get(LogConstants::LOG_QUEUE_NAME);
    }
}
