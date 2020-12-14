<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore;

use Spryker\Zed\CategoryDataImport\Business\Writer\CategoryStore\DataSet\CategoryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class StoreRelationshipFilterStep implements DataImportStepInterface
{
    protected const ALL_STORES_IDENTIFIER = '*';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if ($dataSet[CategoryStoreDataSetInterface::COLUMN_EXCLUDED_STORE_NAME] === static::ALL_STORES_IDENTIFIER) {
            $this->filterAgainstExcludedStores($dataSet);
        }

        $this->filterAgainstIncludedStores($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function filterAgainstExcludedStores(DataSetInterface $dataSet): void
    {
        if ($dataSet[CategoryStoreDataSetInterface::COLUMN_INCLUDED_STORE_NAME] === static::ALL_STORES_IDENTIFIER) {
            $dataSet[CategoryStoreDataSetInterface::INCLUDED_STORE_IDS] = [];

            return;
        }

        $dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS] = array_diff(
            $dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS],
            $dataSet[CategoryStoreDataSetInterface::INCLUDED_STORE_IDS]
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function filterAgainstIncludedStores(DataSetInterface $dataSet): void
    {
        $dataSet[CategoryStoreDataSetInterface::INCLUDED_STORE_IDS] = array_diff(
            $dataSet[CategoryStoreDataSetInterface::INCLUDED_STORE_IDS],
            $dataSet[CategoryStoreDataSetInterface::EXCLUDED_STORE_IDS]
        );
    }
}
