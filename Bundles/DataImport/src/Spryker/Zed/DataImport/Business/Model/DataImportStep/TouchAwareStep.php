<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

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
    protected $mainTouchables = [];

    /**
     * @var array
     */
    protected $subTouchables = [];

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
     *
     * @return void
     */
    public function addMainTouchable($itemType, $itemId)
    {
        $this->mainTouchables[$itemType][] = $itemId;
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return void
     */
    public function addSubTouchable($itemType, $itemId)
    {
        $this->subTouchables[$itemType][] = $itemId;
    }

    /**
     * @return void
     */
    public function afterExecute()
    {
        if (empty($this->mainTouchables)) {
            return;
        }

        $mainTouchAblesItemKey = key($this->mainTouchables);

        if (!$this->bulkSize || $this->bulkSize === 1 || count($this->mainTouchables[$mainTouchAblesItemKey]) >= $this->bulkSize) {
            $itemIds = $this->mainTouchables[$mainTouchAblesItemKey];
            if ($itemIds) {
                $this->touchFacade->bulkTouchSetActive($mainTouchAblesItemKey, array_unique($itemIds));
            }
            $this->mainTouchables = [];

            if (count($this->subTouchables) > 0) {
                foreach ($this->subTouchables as $subTouchAbleItemKey => $itemIds) {
                    if ($itemIds) {
                        $this->touchFacade->bulkTouchSetActive($subTouchAbleItemKey, array_unique($itemIds));
                    }
                }
                $this->subTouchables = [];
            }
        }
    }

    /**
     * Make sure that if bulk is used but not reached, all current entries still touched.
     */
    public function __destruct()
    {
        foreach ($this->mainTouchables as $touchType => $itemIds) {
            $this->touchFacade->bulkTouchSetActive($touchType, array_unique($itemIds));
            $this->mainTouchables = [];
        }

        foreach ($this->subTouchables as $touchType => $itemIds) {
            $this->touchFacade->bulkTouchSetActive($touchType, array_unique($itemIds));
        }
        $this->mainTouchables = [];
    }

}
