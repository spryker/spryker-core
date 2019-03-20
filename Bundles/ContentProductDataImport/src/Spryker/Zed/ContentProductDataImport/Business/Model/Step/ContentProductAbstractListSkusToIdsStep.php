<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ContentProductDataImport\Business\Model\Step;

use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Orm\Zed\Product\Persistence\Map\SpyProductAbstractTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\ContentProductDataImport\Business\Model\DataSet\ContentProductAbstractListDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
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
    protected $cachedLocales = [];

    /**
     * @var array
     */
    protected $cachedAbstractProductSkusToIds = [];

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @param \Spryker\Shared\Kernel\Store $store
     */
    public function __construct(Store $store)
    {
        $this->store = $store;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $dataSet = $this->setLocales($dataSet);

        foreach ($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_LOCALES] as $localeName) {
            $skusLocaleKey = ContentProductAbstractListDataSetInterface::COLUMN_SKUS . '.' . $localeName;

            if (!isset($dataSet[$skusLocaleKey]) || !$dataSet[$skusLocaleKey]) {
                continue;
            }

            $idsLocaleKey = ContentProductAbstractListDataSetInterface::COLUMN_IDS . '.' . $localeName;
            $dataSet[$idsLocaleKey] = $this->getAbstractProductIds($dataSet, $skusLocaleKey);
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $skusLocaleKey
     *
     * @return array
     */
    protected function getAbstractProductIds(DataSetInterface $dataSet, string $skusLocaleKey): array
    {
        $abstractProductSkus = explode(',', $dataSet[$skusLocaleKey]);
        $abstractProductIds = $this->getCachedAbstractProductIdsBySkus($abstractProductSkus);
        $refactoredAbstractProductSkus = $this->refactoringAbstractProductSkus($abstractProductSkus);

        if (count($refactoredAbstractProductSkus) > 0) {
            $abstractProductIdsFromDb = $this->getAbstractProductIdsBySkus($refactoredAbstractProductSkus);

            if (count($abstractProductIdsFromDb) < count($refactoredAbstractProductSkus)) {
                $rowKey = $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PROCUCT_ABSTRACT_LIST_KEY];
                $parameters = [
                    static::EXCEPTION_ERROR_MESSAGE_PARAMETER_COLUMN => $skusLocaleKey,
                    static::EXCEPTION_ERROR_MESSAGE_PARAMETER_KEY => $rowKey,
                ];

                $this->creteInvalidDataImportException(static::EXCEPTION_ERROR_MESSAGE_SKUS_TO_IDS, $parameters);
            }

            $abstractProductIds = array_merge($abstractProductIds, $abstractProductIdsFromDb);
            $this->addAbstractProductIdsToCache($abstractProductSkus, $abstractProductIds);
        }

        return $abstractProductIds;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface
     */
    protected function setLocales(DataSetInterface $dataSet): DataSetInterface
    {
        if (!$this->cachedLocales) {
            $this->setCachedLocales();
        }

        $dataSet[ContentProductAbstractListDataSetInterface::COLUMN_LOCALES] = $this->cachedLocales;

        return $dataSet;
    }

    /**
     * @return void
     */
    protected function setCachedLocales(): void
    {
        $this->cachedLocales[0] = static::LOCALE_NAME_DEFAULT;

        $localeEntityCollection = SpyLocaleQuery::create()
            ->filterByLocaleName_In($this->getLocales())
            ->find();

        foreach ($localeEntityCollection as $localeEntity) {
            $this->cachedLocales[$localeEntity->getIdLocale()] = $localeEntity->getLocaleName();
        }
    }

    /**
     * @return array
     */
    protected function getLocales(): array
    {
        $locales = $this->store->getLocales();

        foreach ($this->store->getStoresWithSharedPersistence() as $storeName) {
            $locales = array_merge($locales, $this->store->getLocalesPerStore($storeName));
        }

        return $locales;
    }

    /**
     * @param string[] $abstractProductSkus
     *
     * @return array
     */
    protected function getCachedAbstractProductIdsBySkus(array $abstractProductSkus): array
    {
        $cachedAbstractProductIds = [];

        foreach ($abstractProductSkus as $key => $abstractProductSku) {
            if (!isset($this->cachedAbstractProductSkusToIds[$abstractProductSku])) {
                continue;
            }

            $cachedAbstractProductIds[$abstractProductSku] = $this->cachedAbstractProductSkusToIds[$abstractProductSku];
            unset($abstractProductSkus[$key]);
        }

        return $cachedAbstractProductIds;
    }

    /**
     * @param string[] $abstractProductSkus
     *
     * @return array
     */
    protected function refactoringAbstractProductSkus(array $abstractProductSkus): array
    {
        $refactoredAbstractProductSkus = [];

        foreach ($abstractProductSkus as $abstractProductSku) {
            if (isset($this->cachedAbstractProductSkusToIds[$abstractProductSku])) {
                continue;
            }

            $refactoredAbstractProductSkus[] = $abstractProductSku;
        }

        return $refactoredAbstractProductSkus;
    }

    /**
     * @param string[] $abstractProductSkus
     *
     * @return array
     */
    protected function getAbstractProductIdsBySkus(array $abstractProductSkus): array
    {
        $abstractProductEntity = SpyProductAbstractQuery::create()
            ->filterBySku_In($abstractProductSkus)
            ->select(SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT)
            ->find();

        return $abstractProductEntity->toArray();
    }

    /**
     * @param string[] $abstractProductSkus
     * @param int[] $abstractProductIds
     *
     * @return void
     */
    protected function addAbstractProductIdsToCache(array $abstractProductSkus, array $abstractProductIds): void
    {
        $abstractProductSkus = array_values($abstractProductSkus);
        $abstractProductIds = array_values($abstractProductIds);
        $abstractProductIdsToCache = array_combine($abstractProductSkus, $abstractProductIds);

        if (is_array($abstractProductIdsToCache)) {
            $this->cachedAbstractProductSkusToIds += $abstractProductIdsToCache;
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
    protected function creteInvalidDataImportException(string $message, array $parameters)
    {
        $errorMessage = strtr($message, $parameters);

        throw new InvalidDataException($errorMessage);
    }
}
