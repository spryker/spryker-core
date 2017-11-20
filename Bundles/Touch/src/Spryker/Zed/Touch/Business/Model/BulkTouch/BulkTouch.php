<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Touch\Business\Model\BulkTouch;

class BulkTouch implements BulkTouchInterface
{
    /**
     * @var \Spryker\Zed\Touch\Business\Model\BulkTouch\BulkTouchInterface[]
     */
    protected $bulkTouchHandler;

    /**
     * @param array $bulkTouchHandler
     */
    public function __construct(array $bulkTouchHandler)
    {
        $this->bulkTouchHandler = $bulkTouchHandler;
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
        $affectedRows = 0;

        foreach ($this->bulkTouchHandler as $bulkTouchHandler) {
            $affectedRows += $bulkTouchHandler->bulkTouch($itemType, $itemEvent, $itemIds);
        }

        return $affectedRows;
    }
}
