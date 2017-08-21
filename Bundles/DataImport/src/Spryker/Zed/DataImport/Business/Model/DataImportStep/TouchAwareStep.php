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
    protected $mainTouchables = [
        SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE => [],
        SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE => [],
        SpyTouchTableMap::COL_ITEM_EVENT_DELETED => [],
    ];

    /**
     * @var array
     */
    protected $subTouchables = [
        SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE => [],
        SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE => [],
        SpyTouchTableMap::COL_ITEM_EVENT_DELETED => [],
    ];

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
        if (!isset($this->mainTouchables[$itemEvent][$itemType])) {
            $this->mainTouchables[$itemEvent][$itemType] = [];
        }

        $this->mainTouchables[$itemEvent][$itemType][] = $itemId;
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
        if (!isset($this->subTouchables[$itemEvent][$itemType])) {
            $this->subTouchables[$itemEvent][$itemType] = [];
        }

        $this->subTouchables[$itemEvent][$itemType][] = $itemId;
    }

    /**
     * @return void
     */
    public function afterExecute()
    {
        $touchableCount = $this->getTouchableCount($this->mainTouchables);

        if ($touchableCount === 0) {
            return;
        }

        if (!$this->bulkSize || $this->bulkSize === 1 || $touchableCount >= $this->bulkSize) {
            $this->flushTouchables($this->mainTouchables);
            $this->flushTouchables($this->subTouchables);
        }
    }

    /**
     * @return void
     */
    public function flush()
    {
        $this->flushTouchables($this->mainTouchables);
        $this->flushTouchables($this->subTouchables);
    }

    /**
     * @param array $touchables
     *
     * @return int
     */
    protected function getTouchableCount(array $touchables)
    {
        $count = 0;
        foreach ($touchables as $itemEventTouchables) {
            $count += count($itemEventTouchables);
        }

        return $count;
    }

    /**
     * @param array $touchables
     *
     * @return void
     */
    protected function flushTouchables(array $touchables)
    {
        $this->flushActiveTouchables($touchables[SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE]);
        $this->flushInActiveTouchables($touchables[SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE]);
        $this->flushDeletedTouchables($touchables[SpyTouchTableMap::COL_ITEM_EVENT_DELETED]);
    }

    /**
     * @param array $touchables
     *
     * @return void
     */
    protected function flushActiveTouchables(array $touchables)
    {
        foreach ($touchables as $itemKey => $itemIds) {
            $this->touchFacade->bulkTouchSetActive($itemKey, array_unique($itemIds));
            $this->mainTouchables[SpyTouchTableMap::COL_ITEM_EVENT_ACTIVE] = [];
        }
    }

    /**
     * @param array $touchables
     *
     * @return void
     */
    protected function flushInActiveTouchables(array $touchables)
    {
        foreach ($touchables as $itemKey => $itemIds) {
            $this->touchFacade->bulkTouchSetInActive($itemKey, array_unique($itemIds));
            $this->mainTouchables[SpyTouchTableMap::COL_ITEM_EVENT_INACTIVE] = [];
        }
    }

    /**
     * @param array $touchables
     *
     * @return void
     */
    protected function flushDeletedTouchables(array $touchables)
    {
        foreach ($touchables as $itemKey => $itemIds) {
            $this->touchFacade->bulkTouchSetDeleted($itemKey, array_unique($itemIds));
            $this->mainTouchables[SpyTouchTableMap::COL_ITEM_EVENT_DELETED] = [];
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
