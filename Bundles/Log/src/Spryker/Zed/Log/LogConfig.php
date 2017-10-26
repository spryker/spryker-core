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
     * @return string
     */
    public function getChannelName()
    {
        return $this->get(LogConstants::LOGGER_CHANNEL_ZED, 'Zed');
    }

    /**
     * @return mixed
     */
    public function getSanitizerFieldNames()
    {
        return $this->get(LogConstants::LOG_SANITIZE_FIELDS, []);
    }

    /**
     * @return mixed
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
     * @return string
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
        $logDirectoryConstants = [
            LogConstants::LOG_FILE_PATH_YVES,
            LogConstants::LOG_FILE_PATH_ZED,
            LogConstants::LOG_FILE_PATH,
            LogConstants::EXCEPTION_LOG_FILE_PATH_YVES,
            LogConstants::EXCEPTION_LOG_FILE_PATH_ZED,
            LogConstants::EXCEPTION_LOG_FILE_PATH,
        ];
        $logFileDirectories = [];

        foreach ($logDirectoryConstants as $logDirectoryConstant) {
            if ($this->getConfig()->hasKey($logDirectoryConstant)) {
                $logFileDirectories[] = dirname($this->get($logDirectoryConstant));
            }
        }

        return array_unique($logFileDirectories);
    }
}
