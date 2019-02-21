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
     */
    protected const BUFFER_LIMIT = 1000;

    /**
     * Sets that the messages that are handled can bubble up the stack or not.
     */
    protected const BUBBLE = true;

    /**
     * Sets is buffer have to be flushed when the max size has been reached, by default oldest entries are discarded.
     */
    protected const FLUSH_ON_OVERFLOW = true;

    /**
     * @var string[]
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
        LogConstants::LOG_FOLDER_PATH_INSTALLATION,
    ];

    /**
     * @return string
     */
    public function getChannelName()
    {
        return $this->get(LogConstants::LOGGER_CHANNEL_ZED, 'Zed');
    }

    /**
     * @return string[]
     */
    public function getSanitizerFieldNames()
    {
        return $this->get(LogConstants::LOG_SANITIZE_FIELDS, []);
    }

    /**
     * @return string
     */
    public function getSanitizedFieldValue()
    {
        return $this->get(LogConstants::LOG_SANITIZED_VALUE, '***');
    }

    /**
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
     * @return int|string Level or level name
     */
    public function getLogLevel()
    {
        return $this->get(LogConstants::LOG_LEVEL);
    }

    /**
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
     * @return array
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
     * @return string
     */
    public function getQueueName()
    {
        return $this->get(LogConstants::LOG_QUEUE_NAME);
    }

    /**
     * @return int
     */
    public function getBufferLimit(): int
    {
        return static::BUFFER_LIMIT;
    }

    /**
     * @return bool
     */
    public function getIsBubble(): bool
    {
        return static::BUBBLE;
    }

    /**
     * @return bool
     */
    public function getIsFlushOnOverflow(): bool
    {
        return static::FLUSH_ON_OVERFLOW;
    }
}
