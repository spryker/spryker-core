<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log;

use Spryker\Shared\Log\LogConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LogConfig extends AbstractBundleConfig
{
    /**
     * Sets how many entries should be buffered at most, beyond that the oldest items are removed from the buffer.
     *
     * @var int
     */
    protected const BUFFER_LIMIT = 1000;

    /**
     * Sets that the messages that are handled can bubble up the stack or not.
     *
     * @var bool
     */
    protected const BUBBLE = true;

    /**
     * Sets is buffer have to be flushed when the max size has been reached, by default oldest entries are discarded.
     *
     * @var bool
     */
    protected const FLUSH_ON_OVERFLOW = true;

    /**
     * @var array<string>
     */
    protected $logDirectoryConstants = [
        LogConstants::LOG_FILE_PATH_YVES,
        LogConstants::LOG_FILE_PATH_ZED,
        LogConstants::LOG_FILE_PATH_GLUE,
        LogConstants::LOG_FILE_PATH,
        LogConstants::EXCEPTION_LOG_FILE_PATH_YVES,
        LogConstants::EXCEPTION_LOG_FILE_PATH_ZED,
        LogConstants::EXCEPTION_LOG_FILE_PATH_GLUE,
        LogConstants::EXCEPTION_LOG_FILE_PATH,
    ];

    /**
     * @api
     *
     * @return string
     */
    public function getChannelName()
    {
        return $this->get(LogConstants::LOGGER_CHANNEL_ZED, 'Zed');
    }

    /**
     * @api
     *
     * @return array<string>
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
     * @return string
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
     * - Defines the log destination path, e.g 'php://stderr' or '/data/log/Zed/application.log'.
     *
     * @api
     *
     * @return resource|string
     */
    public function getLogDestinationPath()
    {
        return $this->get(LogConstants::LOG_FILE_PATH_ZED, 'php://stderr');
    }

    /**
     * @api
     *
     * @deprecated Use {@link getLogDestinationPath()} instead.
     *
     * @return string
     */
    public function getLogFilePath()
    {
        if ($this->getConfig()->hasKey(LogConstants::LOG_FILE_PATH_ZED)) {
            return $this->get(LogConstants::LOG_FILE_PATH_ZED);
        }

        return $this->get(LogConstants::LOG_FILE_PATH);
    }

    /**
     * @api
     *
     * @return string|int Level or level name
     */
    public function getLogLevel()
    {
        return $this->get(LogConstants::LOG_LEVEL);
    }

    /**
     * Specification:
     * - Defines the log destination path, e.g 'php://stderr' or '/data/log/Zed/exception.log'.
     *
     * @api
     *
     * @return resource|string
     */
    public function getExceptionLogDestinationPath()
    {
        return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH_ZED, 'php://stderr');
    }

    /**
     * @api
     *
     * @deprecated Use {@link getExceptionLogDestinationPath()} instead.
     *
     * @return string
     */
    public function getExceptionLogFilePath()
    {
        if ($this->getConfig()->hasKey(LogConstants::EXCEPTION_LOG_FILE_PATH_ZED)) {
            return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH_ZED);
        }

        return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH);
    }

    /**
     * @api
     *
     * @return array<string>
     */
    public function getLogFileDirectories()
    {
        $logFileDirectories = [];

        foreach ($this->logDirectoryConstants as $logDirectoryConstant) {
            if ($this->getConfig()->hasKey($logDirectoryConstant)) {
                $logFileDirectories[] = dirname($this->get($logDirectoryConstant));
            }
        }

        return array_unique($logFileDirectories);
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

    /**
     * @api
     *
     * @return int
     */
    public function getBufferLimit(): int
    {
        return static::BUFFER_LIMIT;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getIsBubble(): bool
    {
        return static::BUBBLE;
    }

    /**
     * @api
     *
     * @return bool
     */
    public function getIsFlushOnOverflow(): bool
    {
        return static::FLUSH_ON_OVERFLOW;
    }
}
