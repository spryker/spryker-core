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
     * Specification:
     * - Provides an array with names which is used to sanitize data in your audit logs.
     *
     * @api
     *
     * @return list<string>
     */
    public function getAuditLogSanitizerFieldNames(): array
    {
        return $this->get(LogConstants::AUDIT_LOG_SANITIZE_FIELDS, []);
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
     * - Provides a string which is used as value for the audit log sanitized fields.
     *
     * @api
     *
     * @return string
     */
    public function getAuditLogSanitizedFieldValue(): string
    {
        return $this->get(LogConstants::AUDIT_LOG_SANITIZED_VALUE, '***');
    }

    /**
     * Specification:
     * - Provides a list of audit log tags that are disallowed for logging.
     *
     * @api
     *
     * @return list<string>
     */
    public function getAuditLogTagDisallowList(): array
    {
        return $this->get(LogConstants::AUDIT_LOG_TAG_DISALLOW_LIST, []);
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
        return $this->get(LogConstants::LOG_FILE_PATH_YVES, 'php://stderr');
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
     * @return string|int
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
        return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH_YVES, 'php://stderr');
    }

    /**
     * @api
     *
     * @deprecated Use {@link \Spryker\Yves\Log\LogConfig::getExceptionLogDestinationPath()} instead.
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
