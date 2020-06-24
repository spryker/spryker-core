<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch;

use Spryker\Zed\Touch\TouchConfig;

class BulkTouch implements BulkTouchInterface
{
    /**
     * @var \Spryker\Zed\Touch\Business\Model\BulkTouch\BulkTouchInterface[]
     */
    protected $bulkTouchHandler;

    /**
     * @var \Spryker\Zed\Touch\TouchConfig
     */
    protected $touchConfig;

    /**
     * @param array $bulkTouchHandler
     * @param \Spryker\Zed\Touch\TouchConfig $touchConfig
     */
    public function __construct(array $bulkTouchHandler, TouchConfig $touchConfig)
    {
        $this->bulkTouchHandler = $bulkTouchHandler;
        $this->touchConfig = $touchConfig;
    }

    /**
     * @param string $itemType
     * @param string $itemEvent
     * @param array $itemIds
     *
     * @return int
     */
    public function bulkTouch($itemType, $itemEvent, array $itemIds)
    {
        if (!$this->touchConfig->isTouchEnabled()) {
            return 0;
        }

        $affectedRows = 0;

        foreach ($this->bulkTouchHandler as $bulkTouchHandler) {
            $affectedRows += $bulkTouchHandler->bulkTouch($itemType, $itemEvent, $itemIds);
        }

        return $affectedRows;
    }
}
