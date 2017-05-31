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
    protected $mainTouchAbles = [];

    /**
     * @var array
     */
    protected $subTouchAbles = [];

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
        $this->mainTouchAbles[$itemType][] = $itemId;
    }

    /**
     * @param string $itemType
     * @param int $itemId
     *
     * @return void
     */
    public function addSubTouchable($itemType, $itemId)
    {
        $this->subTouchAbles[$itemType][] = $itemId;
    }

    /**
     * @return void
     */
    public function afterExecute()
    {
        if (count($this->mainTouchAbles) === 0) {
            return;
        }

        $mainTouchAblesItemKey = key($this->mainTouchAbles);

        if (!$this->bulkSize || $this->bulkSize === 1 || $this->bulkSize >= count($this->mainTouchAbles[$mainTouchAblesItemKey])) {
            $itemIds = $this->mainTouchAbles[$mainTouchAblesItemKey];
            if ($itemIds) {
                $this->touchFacade->bulkTouchSetActive($mainTouchAblesItemKey, array_unique($itemIds));
            }
            $this->mainTouchAbles = [];

            if (count($this->subTouchAbles) > 0) {
                foreach ($this->subTouchAbles as $subTouchAbleItemKey => $itemIds) {
                    if ($itemIds) {
                        $this->touchFacade->bulkTouchSetActive($subTouchAbleItemKey, array_unique($itemIds));
                    }
                }
                $this->subTouchAbles = [];
            }
        }
    }

    /**
     * Make sure that if bulk is used but not reached, all current entries still touched.
     */
    public function __destruct()
    {
        foreach ($this->mainTouchAbles as $touchType => $itemIds) {
            $this->touchFacade->bulkTouchSetActive($touchType, array_unique($itemIds));
            $this->mainTouchAbles = [];
        }

        foreach ($this->subTouchAbles as $touchType => $itemIds) {
            $this->touchFacade->bulkTouchSetActive($touchType, array_unique($itemIds));
        }
        $this->mainTouchAbles = [];
    }

}
