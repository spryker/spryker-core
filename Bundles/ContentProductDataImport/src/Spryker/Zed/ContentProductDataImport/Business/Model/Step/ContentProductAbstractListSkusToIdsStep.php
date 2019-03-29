<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddLocalesStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class ContentProductAbstractListSkusToIdsStep implements DataImportStepInterface
{
    protected const EXCEPTION_ERROR_MESSAGE_SKUS_TO_IDS = 'Found not valid skus in the row with key:"{key}", column:"{column}"';
    protected const EXCEPTION_ERROR_MESSAGE_PARAMETER_COLUMN = '{column}';
    protected const EXCEPTION_ERROR_MESSAGE_PARAMETER_KEY = '{key}';

    /**
     * @var array
     */
    protected $cachedProductAbstractSkusToIds = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        foreach ($dataSet[AddLocalesStep::KEY_LOCALES] as $localeName => $idLocale) {
            $skusLocaleKey = ContentProductAbstractListDataSetInterface::COLUMN_SKUS . '.' . $localeName;

            if (!isset($dataSet[$skusLocaleKey]) || !$dataSet[$skusLocaleKey]) {
                continue;
            }

            $localeKeyIds = ContentProductAbstractListDataSetInterface::COLUMN_IDS . '.' . $localeName;
            $dataSet[$localeKeyIds] = $this->getProductAbstractIds($dataSet, $skusLocaleKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $skusLocaleKey
     *
     * @return array
     */
    protected function getProductAbstractIds(DataSetInterface $dataSet, string $skusLocaleKey): array
    {
        $productAbstractSkus = explode(',', $dataSet[$skusLocaleKey]);
        $productAbstractIds = $this->getProductAbstractIdsBySkus($productAbstractSkus);

        if (count($productAbstractIds) < count($productAbstractSkus)) {
            $parameters = [
                static::EXCEPTION_ERROR_MESSAGE_PARAMETER_COLUMN => $skusLocaleKey,
                static::EXCEPTION_ERROR_MESSAGE_PARAMETER_KEY => $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY],
            ];

            $this->createInvalidDataImportException(static::EXCEPTION_ERROR_MESSAGE_SKUS_TO_IDS, $parameters);
        }

        return $this->sortProductAbstractIds($productAbstractSkus, $productAbstractIds);
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return array
     */
    protected function getProductAbstractIdsBySkus(array $productAbstractSkus): array
    {
        $productAbstractIds = [];

        foreach ($productAbstractSkus as $key => $productAbstractSku) {
            if (!isset($this->cachedProductAbstractSkusToIds[$productAbstractSku])) {
                continue;
            }

            $productAbstractIds[$productAbstractSku] = $this->cachedProductAbstractSkusToIds[$productAbstractSku];
            unset($productAbstractSkus[$key]);
        }

        if (!$productAbstractSkus) {
            return $productAbstractIds;
        }

        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku_In($productAbstractSkus)
            ->select([SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT, SpyProductAbstractTableMap::COL_SKU])
            ->find();

        foreach ($productAbstractEntity->toArray() as $productAbstract) {
            $productAbstractIds[$productAbstract[SpyProductAbstractTableMap::COL_SKU]] = $productAbstract[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];
            $this->cachedProductAbstractSkusToIds[$productAbstract[SpyProductAbstractTableMap::COL_SKU]] = $productAbstract[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];
        }

        return $productAbstractIds;
    }

    /**
     * @param string $message
     * @param array $parameters
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    protected function createInvalidDataImportException(string $message, array $parameters)
    {
        $errorMessage = strtr($message, $parameters);

        throw new InvalidDataException($errorMessage);
    }

    /**
     * @param string[] $productAbstractSkus
     * @param int[] $productAbstractIds
     *
     * @return int[]
     */
    protected function sortProductAbstractIds(array $productAbstractSkus, array $productAbstractIds): array
    {
        $sortedProductAbstractIds = [];

        foreach ($productAbstractSkus as $productAbstractSku) {
            $sortedProductAbstractIds[] = $productAbstractIds[$productAbstractSku];
        }

        return $sortedProductAbstractIds;
    }
}
