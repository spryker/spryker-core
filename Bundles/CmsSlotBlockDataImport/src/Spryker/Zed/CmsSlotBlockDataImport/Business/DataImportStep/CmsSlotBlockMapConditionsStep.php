<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockDataImport\Business\DataImportStep;

use Spryker\Zed\CmsSlotBlockDataImport\Business\DataSet\CmsSlotBlockDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CmsSlotBlockMapConditionsStep implements DataImportStepInterface
{
    protected const CONDITIONS = 'conditions';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $conditionKeys = $this->getConditionKeys($dataSet);
        $dataSet[CmsSlotBlockDataSetInterface::CMS_SLOT_BLOCK_ALL_CONDITIONS] = $this->mapConditionsToArray(
            $dataSet,
            $conditionKeys
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return string[]
     */
    protected function getConditionKeys(DataSetInterface $dataSet): array
    {
        $keys = array_keys($dataSet->getArrayCopy());
        $conditionKeys = [];

        foreach ($keys as $key) {
            if (strpos($key, self::CONDITIONS) !== false) {
                $conditionKeys[] = $key;
            }
        }

        return $conditionKeys;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string[] $conditionKeys
     *
     * @return array
     */
    protected function mapConditionsToArray(DataSetInterface $dataSet, array $conditionKeys): array
    {
        $conditionsArray = [];

        foreach ($conditionKeys as $conditionKey) {
            $conditionsArrayKeys = explode('.', $conditionKey);
            unset($conditionsArrayKeys[0]);
            $conditionsArray = $this->addConditionToArrayRecursive($conditionsArray, $conditionsArrayKeys, $dataSet[$conditionKey]);
        }

        return $conditionsArray;
    }

    /**
     * @param array $conditionsArray
     * @param string[] $conditionsArrayKeys
     * @param mixed $value
     *
     * @return array|string
     */
    protected function addConditionToArrayRecursive(array $conditionsArray, array $conditionsArrayKeys, $value)
    {
        if (!$conditionsArrayKeys) {
            return $value;
        }

        $conditionArrayKey = array_shift($conditionsArrayKeys);

        if ($value === '') {
            $value = [];
        }

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        if (!isset($conditionsArray[$conditionArrayKey])) {
            $conditionsArray[$conditionArrayKey] = [];
        }

        $conditionsArray[$conditionArrayKey] = $this->addConditionToArrayRecursive(
            $conditionsArray[$conditionArrayKey],
            $conditionsArrayKeys,
            $value
        );

        return $conditionsArray;
    }
}
