<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Loggly;

use Spryker\Shared\Loggly\LogglyConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class LogglyConfig extends AbstractBundleConfig
{

    const QUEUE_NAME_DEFAULT = 'loggly-log-queue';

    /**
     * @return \Spryker\Shared\Config\Config
     */
    public function getLogglyToken()
    {
        return $this->get(LogglyConstants::TOKEN);
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        return $this->get(LogglyConstants::QUEUE_NAME, static::QUEUE_NAME_DEFAULT);
    }

    /**
     * @return string
     */
    public function getQueueChunkSize()
    {
        return $this->get(LogglyConstants::QUEUE_CHUNK_SIZE, 50);
    }

}
