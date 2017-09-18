<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface;

class TouchAwareStep implements DataImportStepAfterExecuteInterface
{

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var array
     */
    protected $touchables = [];

    /**
     * @var int
     */
    protected $touchableCount = 0;

    /**
     * @var int|null
     */
    protected $bulkSize;

    /**
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface $touchFacade
     * @param null|int $bulkSize
     */
    public function __construct(DataImportToTouchInterface $touchFacade, $bulkSize = null)
    {
        $this->touchFacade = $touchFacade;
        $this->bulkSize = $bulkSize;
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $itemEvent
     *
     * @return void
     */
    public function addMainTouchable($itemType, $itemId, $itemEvent = SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
    {
        $this->touchableCount++;

        $this->addTouchable($itemType, $itemId, $itemEvent);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $itemEvent
     *
     * @return void
     */
    public function addSubTouchable($itemType, $itemId, $itemEvent = SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
    {
        $this->addTouchable($itemType, $itemId, $itemEvent);
    }

    /**
     * @param string $itemType
     * @param int $itemId
     * @param string $itemEvent
     *
     * @return void
     */
    protected function addTouchable($itemType, $itemId, $itemEvent = SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE)
    {
        if (!isset($this->touchables[$itemEvent])) {
            $this->touchables[$itemEvent] = [];
        }

        if (!isset($this->touchables[$itemEvent][$itemType])) {
            $this->touchables[$itemEvent][$itemType] = [];
        }

        $this->touchables[$itemEvent][$itemType][] = $itemId;
    }

    /**
     * @return void
     */
    public function afterExecute()
    {
        if ($this->touchableCount === 0) {
            return;
        }

        if (!$this->bulkSize || $this->bulkSize === 1 || $this->touchableCount >= $this->bulkSize) {
            $this->flushTouchables();
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->flushTouchables();
    }

    /**
     * @return void
     */
    protected function flushTouchables()
    {
        foreach ($this->touchables as $itemEvent => $touchableTypes) {
            foreach ($touchableTypes as $itemType => $touchables) {
                if ($itemEvent === SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE) {
                    $this->touchFacade->bulkTouchSetActive($itemType, array_unique($touchables));
                }
                if ($itemEvent === SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE) {
                    $this->touchFacade->bulkTouchSetInactive($itemType, array_unique($touchables));
                }
                if ($itemEvent === SpyTouchTableMap::COL_ITEM_EVENT_DELETED) {
                    $this->touchFacade->bulkTouchSetDeleted($itemType, array_unique($touchables));
                }
            }
            $this->touchables[$itemEvent] = [];
        }
    }

    /**
     * Make sure that if bulk is used but not reached, all current entries still touched.
     */
    public function __destruct()
    {
        $this->flush();
    }

}
