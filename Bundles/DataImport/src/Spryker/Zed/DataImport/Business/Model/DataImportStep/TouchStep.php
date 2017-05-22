<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface;

class TouchStep implements DataImportStepInterface
{

    /**
     * @var \Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface
     */
    protected $touchFacade;

    /**
     * @var array
     */
    protected $touchAbles = [];

    /**
     * @var int|null
     */
    protected $bulkSize;

    /**
     * @var array
     */
    protected $executed = [];

    /**
     * @var string
     */
    protected $itemTypeKey;

    /**
     * @var string
     */
    protected $itemIdKey;

    /**
     * @param string $itemTypeKey
     * @param string $itemIdKey
     * @param \Spryker\Zed\DataImport\Dependency\Facade\DataImportToTouchInterface $touchFacade
     * @param null|int $bulkSize
     */
    public function __construct($itemTypeKey, $itemIdKey, DataImportToTouchInterface $touchFacade, $bulkSize = null)
    {
        $this->itemTypeKey = $itemTypeKey;
        $this->itemIdKey = $itemIdKey;
        $this->touchFacade = $touchFacade;
        $this->bulkSize = $bulkSize;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        if (!isset($dataSet[$this->itemTypeKey]) || !isset($dataSet[$this->itemIdKey])) {
            return;
        }

        $touchType = $dataSet[$this->itemTypeKey];
        $touchItemId = $dataSet[$this->itemIdKey];
        $touchChangeKey = false;

        if (!$this->bulkSize || $this->bulkSize === 1) {
            $this->touchFacade->touchActive($touchType, $touchItemId, $touchChangeKey);

            return;
        }

        if (!isset($this->touchAbles[$touchType])) {
            $this->touchAbles[$touchType] = [];
        }
        $this->touchAbles[$touchType][] = $touchItemId;

        if (!isset($this->executed[$touchType])) {
            $this->executed[$touchType] = 0;
        }
        $this->executed[$touchType] = ++$this->executed[$touchType];

        if ($this->bulkSize === count($this->touchAbles[$touchType])) {
            $this->touchFacade->bulkTouchSetActive($touchType, array_unique($this->touchAbles[$touchType]));
            unset($this->touchAbles[$touchType]);
            $this->executed[$touchType] = 0;
        }
    }

    /**
     * Make sure that if bulk is used but not reached, all current entries still touched.
     */
    public function __destruct()
    {
        foreach ($this->touchAbles as $touchType => $itemIds) {
            $this->touchFacade->bulkTouchSetActive($touchType, array_unique($itemIds));
            unset($this->touchAbles[$touchType]);
        }
    }

}
