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
        return $this->get(LogConstants::EXCEPTION_LOG_FILE_PATH);
    }

    /**
     * @return array
     */
    public function getLogFileDirectories()
    {
        return [
            dirname($this->get(LogConstants::LOG_FILE_PATH)),
            dirname($this->get(LogConstants::EXCEPTION_LOG_FILE_PATH)),
        ];
    }
}
