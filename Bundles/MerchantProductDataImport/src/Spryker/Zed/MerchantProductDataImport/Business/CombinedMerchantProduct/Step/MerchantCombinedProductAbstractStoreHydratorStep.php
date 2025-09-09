<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Generated\Shared\Transfer\ProductAbstractStoreTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddStoresStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class MerchantCombinedProductAbstractStoreHydratorStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     *
     * @phpstan-var non-empty-string
     */
    public const DELIMITER = ';';

    /**
     * @var string
     */
    public const DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFERS = 'DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFERS';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (!$this->isAssignedProductTypeSupported($dataSet)) {
            return;
        }

        $this->importProductAbstractStore($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function importProductAbstractStore(DataSetInterface $dataSet): void
    {
        if (
            !isset($dataSet[MerchantCombinedProductDataSetInterface::KEY_STORE_RELATIONS])
            || empty($dataSet[MerchantCombinedProductDataSetInterface::KEY_STORE_RELATIONS])
        ) {
            $dataSet[static::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFERS] = [];

            return;
        }

        $this->assertStoreRelationsDelimiter($dataSet[MerchantCombinedProductDataSetInterface::KEY_STORE_RELATIONS]);

        $stores = $this->getStores($dataSet);
        $storeRelations = array_filter(explode(
            static::DELIMITER,
            $dataSet[MerchantCombinedProductDataSetInterface::KEY_STORE_RELATIONS],
        ));

        $productAbstractStoreTransfers = [];
        foreach ($storeRelations as $storeName) {
            $storeName = trim($storeName);

            if (!$storeName) {
                continue;
            }

            if (!isset($stores[$storeName])) {
                throw MerchantCombinedProductException::createWithError(
                    (new ErrorTransfer())
                        ->setMessage('Store name "%s%" is unknown.')
                        ->setParameters(['%s%' => $storeName]),
                );
            }

            $productAbstractStoreTransfer = (new ProductAbstractStoreTransfer())
                ->setStoreName($storeName)
                ->setProductAbstractSku($dataSet[MerchantCombinedProductDataSetInterface::KEY_ABSTRACT_SKU]);

            $productAbstractStoreTransfers[] = $productAbstractStoreTransfer;
        }

        $dataSet[static::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFERS] = $productAbstractStoreTransfers;
    }

    /**
     * @param string $storeRelations
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function assertStoreRelationsDelimiter(string $storeRelations): void
    {
        if (!str_contains($storeRelations, ',')) {
            return;
        }

        throw MerchantCombinedProductException::createWithError(
            (new ErrorTransfer())
                ->setMessage('Store relations should be separated by "%s1%" delimiter, but "%s2%" found.')
                ->setParameters(['%s1%' => static::DELIMITER, '%s2%' => $storeRelations]),
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, int>
     */
    protected function getStores(DataSetInterface $dataSet): array
    {
        return $dataSet[AddStoresStep::KEY_STORES] ?? [];
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_ABSTRACT,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
        ];
    }
}
