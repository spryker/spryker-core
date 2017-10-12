<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Event;

use Spryker\Shared\Event\EventConstants;
use Spryker\Zed\Kernel\AbstractBundleConfig;

class EventConfig extends AbstractBundleConfig
{
    /**
     * @return string|null
     */
    public function findEventLogPath()
    {
        if ($this->getConfig()->hasKey(EventConstants::LOG_FILE_PATH)) {
            return $this->get(EventConstants::LOG_FILE_PATH);
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isLoggerActivated()
    {
        if (!$this->getConfig()->hasKey(EventConstants::LOGGER_ACTIVE)) {
            return false;
        }

        return $this->getConfig()->get(EventConstants::LOGGER_ACTIVE, false);
    }
}
