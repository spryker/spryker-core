<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstractStoreQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\AddStoresStep;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class ProductAbstractStoreWriterStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @param \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository
     */
    public function __construct(protected MerchantCombinedProductRepositoryInterface $merchantCombinedProductRepository)
    {
    }

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

        if (!$this->getProductAbstractStoreTransfers($dataSet)) {
            return;
        }

        $this->createOrUpdateProductAbstractStore($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function createOrUpdateProductAbstractStore(DataSetInterface $dataSet): void
    {
        $productAbstractStoreTransfers = $this->getProductAbstractStoreTransfers($dataSet);

        foreach ($productAbstractStoreTransfers as $productAbstractStoreTransfer) {
            $idProductAbstract = $this->getIdProductAbstractByAbstractSku(
                $productAbstractStoreTransfer->getProductAbstractSkuOrFail(),
            );

            $idStore = $this->getIdStoreByName($dataSet, $productAbstractStoreTransfer->getStoreNameOrFail());

            $productAbstractStoreEntity = (new SpyProductAbstractStoreQuery())
                ->filterByFkProductAbstract($idProductAbstract)
                ->filterByFkStore($idStore)
                ->findOneOrCreate();

            if (!$productAbstractStoreEntity->isNew() && !$productAbstractStoreEntity->isModified()) {
                continue;
            }

            $productAbstractStoreEntity->save();
        }
    }

    /**
     * @param string $sku
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return int
     */
    protected function getIdProductAbstractByAbstractSku(string $sku): int
    {
        try {
            return $this->merchantCombinedProductRepository->getIdProductAbstractByAbstractSku($sku);
        } catch (EntityNotFoundException $e) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())
                    ->setMessage('Cannot import product store relation for product abstract with SKU "%s%". Product abstract not found.')
                    ->setParameters(['%s%' => $sku]),
            );
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $storeName
     *
     * @return int
     */
    protected function getIdStoreByName(DataSetInterface $dataSet, string $storeName): int
    {
        return $dataSet[AddStoresStep::KEY_STORES][$storeName];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<\Generated\Shared\Transfer\ProductAbstractStoreTransfer>
     */
    protected function getProductAbstractStoreTransfers(DataSetInterface $dataSet): array
    {
        return $dataSet[MerchantCombinedProductAbstractStoreHydratorStep::DATA_PRODUCT_ABSTRACT_STORE_ENTITY_TRANSFERS] ?? [];
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
