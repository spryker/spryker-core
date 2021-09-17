<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_SKUS_TO_IDS = 'Found invalid skus in a row with the provided key: "{key}", column: "{column}"';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_DEFAULT_SKUS = '[skus.default] is required. Please check the row with key: "{key}".';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_COLUMN = '{column}';
    /**
     * @var string
     */
    protected const ERROR_MESSAGE_PARAMETER_KEY = '{key}';

    /**
     * @var array
     */
    protected $cachedProductAbstractSkusToIds = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->assureDefaultSkusExists($dataSet);

        $dataSet[AddLocalesStep::KEY_LOCALES] = array_merge($dataSet[AddLocalesStep::KEY_LOCALES], ['default' => null]);

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
     *
     * @return void
     */
    protected function assureDefaultSkusExists(DataSetInterface $dataSet): void
    {
        if (
            !isset($dataSet[ContentProductAbstractListDataSetInterface::COLUMN_DEFAULT_SKUS])
            || !$dataSet[ContentProductAbstractListDataSetInterface::COLUMN_DEFAULT_SKUS]
        ) {
            $parameters = [
                static::ERROR_MESSAGE_PARAMETER_KEY => $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY],
            ];

            $this->createInvalidDataImportException(static::ERROR_MESSAGE_DEFAULT_SKUS, $parameters);
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
        $productAbstractSkus = array_unique(explode(',', $dataSet[$skusLocaleKey]));
        $productAbstractIds = $this->getProductAbstractIdsBySkus($productAbstractSkus);

        if (count($productAbstractIds) < count($productAbstractSkus)) {
            $parameters = [
                static::ERROR_MESSAGE_PARAMETER_COLUMN => $skusLocaleKey,
                static::ERROR_MESSAGE_PARAMETER_KEY => $dataSet[ContentProductAbstractListDataSetInterface::CONTENT_PRODUCT_ABSTRACT_LIST_KEY],
            ];

            $this->createInvalidDataImportException(static::ERROR_MESSAGE_SKUS_TO_IDS, $parameters);
        }

        return $this->sortProductAbstractIds($productAbstractSkus, $productAbstractIds);
    }

    /**
     * @param array<string> $productAbstractSkus
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
            $productAbstractIds[$productAbstract[SpyProductAbstractTableMap::COL_SKU]] = (int)$productAbstract[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];
            $this->cachedProductAbstractSkusToIds[$productAbstract[SpyProductAbstractTableMap::COL_SKU]] = (int)$productAbstract[SpyProductAbstractTableMap::COL_ID_PRODUCT_ABSTRACT];
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
    protected function createInvalidDataImportException(string $message, array $parameters = []): void
    {
        $errorMessage = strtr($message, $parameters);

        throw new InvalidDataException($errorMessage);
    }

    /**
     * @param array<string> $productAbstractSkus
     * @param array<int> $productAbstractIds
     *
     * @return array<int>
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
