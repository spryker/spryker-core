<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DataImport\Business\Model\DataImportStep;

use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class RenameDataSetKeysStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $keyMap;

    /**
     * @param array $keyMap
     */
    public function __construct(array $keyMap)
    {
        $this->keyMap = $keyMap;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        foreach ($this->keyMap as $oldKey => $newKey) {
            $dataSet[$newKey] = $dataSet[$oldKey];
            unset($dataSet[$oldKey]);
        }
    }
}
