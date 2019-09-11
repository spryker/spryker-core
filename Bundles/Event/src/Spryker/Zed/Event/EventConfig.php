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
    public const DEFAULT_EVENT_MESSAGE_CHUNK_SIZE = 1000;
    public const DEFAULT_MAX_RETRY = 1;

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

    /**
     * @return int
     */
    public function getEventQueueMessageChunkSize()
    {
        return $this->get(EventConstants::EVENT_CHUNK, 500);
    }

    /**
     * @return int
     */
    public function getMaxRetryAmount()
    {
        return $this->getConfig()->get(EventConstants::MAX_RETRY_ON_FAIL, static::DEFAULT_MAX_RETRY);
    }

    /**
     * @return bool
     */
    public function isHandleErrorBulkOperationByItemsActive(): bool
    {
        return $this->getConfig()->get(EventConstants::EVENT_HANDLE_ERROR_BULK_OPERATION_BY_ITEMS, false);
    }
}
