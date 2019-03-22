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
    protected const LOCALE_NAME_DEFAULT = 'default';
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
        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], [static::LOCALE_NAME_DEFAULT => null]);

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
        $productAbstractIds = $this->getCachedProductAbstractIdsBySkus($productAbstractSkus);
        $filteredProductAbstractSkus = $this->filterCachedProductAbstractSkus($productAbstractSkus);

        if (count($filteredProductAbstractSkus) > 0) {
            $productAbstractIdsFromDb = $this->getProductAbstractIdsBySkus($filteredProductAbstractSkus);

            if (count($productAbstractIdsFromDb) < count($filteredProductAbstractSkus)) {
                $rowKey = $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY];
                $parameters = [
                    static::EXCEPTION_ERROR_MESSAGE_PARAMETER_COLUMN => $skusLocaleKey,
                    static::EXCEPTION_ERROR_MESSAGE_PARAMETER_KEY => $rowKey,
                ];

                $this->createInvalidDataImportException(static::EXCEPTION_ERROR_MESSAGE_SKUS_TO_IDS, $parameters);
            }

            $productAbstractIds = array_merge($productAbstractIds, $productAbstractIdsFromDb);
            $this->addProductAbstractIdsToCache($productAbstractSkus, $productAbstractIds);
        }

        return $productAbstractIds;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return array
     */
    protected function getCachedProductAbstractIdsBySkus(array $productAbstractSkus): array
    {
        $cachedProductAbstractIds = [];

        foreach ($productAbstractSkus as $key => $productAbstractSku) {
            if (!isset($this->cachedProductAbstractSkusToIds[$productAbstractSku])) {
                continue;
            }

            $cachedProductAbstractIds[$productAbstractSku] = $this->cachedProductAbstractSkusToIds[$productAbstractSku];
            unset($productAbstractSkus[$key]);
        }

        return $cachedProductAbstractIds;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return array
     */
    protected function filterCachedProductAbstractSkus(array $productAbstractSkus): array
    {
        $filteredProductAbstractSkus = [];

        foreach ($productAbstractSkus as $productAbstractSku) {
            if (isset($this->cachedProductAbstractSkusToIds[$productAbstractSku])) {
                continue;
            }

            $filteredProductAbstractSkus[] = $productAbstractSku;
        }

        return $filteredProductAbstractSkus;
    }

    /**
     * @param string[] $productAbstractSkus
     *
     * @return array
     */
    protected function getProductAbstractIdsBySkus(array $productAbstractSkus): array
    {
        $productAbstractEntity = SpyProductAbstractQuery::create()
            ->filterBySku_In($productAbstractSkus)
            ->select(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->find();

        return $productAbstractEntity->toArray();
    }

    /**
     * @param string[] $productAbstractSkus
     * @param int[] $productAbstractIds
     *
     * @return void
     */
    protected function addProductAbstractIdsToCache(array $productAbstractSkus, array $productAbstractIds): void
    {
        $productAbstractSkus = array_values($productAbstractSkus);
        $productAbstractIds = array_values($productAbstractIds);
        $productAbstractIdsToCache = array_combine($productAbstractSkus, $productAbstractIds);

        if (is_array($productAbstractIdsToCache)) {
            $this->cachedProductAbstractSkusToIds += $productAbstractIdsToCache;
        }
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
}
