<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Orm\Zed\Stock\Persistence\SpyStockProductQuery;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Repository\MerchantCombinedProductRepositoryInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class MerchantCombinedProductStockWriterStep implements DataImportStepInterface
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

        $this->createOrUpdateProductStock($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function createOrUpdateProductStock(DataSetInterface $dataSet): void
    {
        $productStockEntityTransfers = $this->getProductStockEntityTransfers($dataSet);
        $sku = $dataSet[MerchantCombinedProductDataSetInterface::KEY_CONCRETE_SKU];
        $idProduct = $this->merchantCombinedProductRepository->getIdProductBySku($sku);

        foreach ($productStockEntityTransfers as $productStockEntityTransfer) {
            $spyStockProduct = SpyStockProductQuery::create()
                ->filterByFkProduct($idProduct)
                ->filterByFkStock($productStockEntityTransfer->getFkStock())
                ->findOneOrCreate();

            $spyStockProduct->fromArray($productStockEntityTransfer->modifiedToArray());

            if (!$spyStockProduct->isNew() && !$spyStockProduct->isModified()) {
                continue;
            }

            $spyStockProduct->save();
        }
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<\Generated\Shared\Transfer\SpyStockProductEntityTransfer>
     */
    protected function getProductStockEntityTransfers(DataSetInterface $dataSet): array
    {
        return $dataSet[MerchantCombinedProductStockHydratorStep::DATA_PRODUCT_STOCK_TRANSFER];
    }

    /**
     * @return array<string>
     */
    protected function getSupportedAssignedProductTypes(): array
    {
        return [
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_CONCRETE,
            MerchantProductDataImportConfig::ASSIGNABLE_PRODUCT_TYPE_BOTH,
        ];
    }
}
