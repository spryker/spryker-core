<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\SpyStockProductEntityTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\MerchantProductDataImportConfig;

class MerchantCombinedProductStockHydratorStep implements DataImportStepInterface
{
    use AssignedProductTypeSupportTrait;

    /**
     * @var string
     */
    public const DATA_PRODUCT_STOCK_TRANSFER = 'DATA_PRODUCT_STOCK_TRANSFER';

    /**
     * @var bool
     */
    protected const DEFAULT_IS_NEVER_OUT_OF_STOCK = false;

    /**
     * @var int
     */
    protected const DEFAULT_STOCK_QUANTITY = 0;

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

        $this->importProductStocks($dataSet);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    protected function importProductStocks(DataSetInterface $dataSet): void
    {
        $productStocks = $this->getProductStocks($dataSet);

        $productStockTransfers = [];
        foreach ($productStocks as $productStock) {
            $warehouseName = $productStock[MerchantCombinedProductStockExtractorStep::KEY_WAREHOUSE_NAME];
            $idStock = $dataSet[AddMerchantStockStep::KEY_MERCHANT_STOCKS][$warehouseName];

            $spyStockProductEntityTransfer = (new SpyStockProductEntityTransfer())
                ->setFkStock($idStock)
                ->setIsNeverOutOfStock(filter_var($productStock[MerchantCombinedProductStockExtractorStep::KEY_IS_NEVER_OUT_OF_STOCK], FILTER_VALIDATE_BOOLEAN))
                ->setQuantity((int)$productStock[MerchantCombinedProductStockExtractorStep::KEY_QUANTITY]);

            $productStockTransfers[] = $spyStockProductEntityTransfer;
        }

        if (!count($productStockTransfers) && $this->getIsNewProduct($dataSet)) {
            $stockIds = array_values($this->getMerchantStocks($dataSet));
            $productStockTransfers = $this->getDefaultStockProductEntityTransfers($stockIds);
        }

        $dataSet[static::DATA_PRODUCT_STOCK_TRANSFER] = $productStockTransfers;
    }

    /**
     * @param array<int> $stockIds
     *
     * @return array<\Generated\Shared\Transfer\SpyStockProductEntityTransfer>
     */
    protected function getDefaultStockProductEntityTransfers(array $stockIds): array
    {
        return array_map(
            static fn (int $idStock): SpyStockProductEntityTransfer => (new SpyStockProductEntityTransfer())
                ->setFkStock($idStock)
                ->setIsNeverOutOfStock(static::DEFAULT_IS_NEVER_OUT_OF_STOCK)
                ->setQuantity(static::DEFAULT_STOCK_QUANTITY),
            $stockIds,
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<\Generated\Shared\Transfer\SpyStockProductEntityTransfer>
     */
    protected function getProductStocks(DataSetInterface $dataSet): array
    {
        return $dataSet[MerchantCombinedProductStockExtractorStep::KEY_PRODUCT_STOCKS];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return array<string, int>
     */
    protected function getMerchantStocks(DataSetInterface $dataSet): array
    {
        return $dataSet[AddMerchantStockStep::KEY_MERCHANT_STOCKS];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function getIsNewProduct(DataSetInterface $dataSet): bool
    {
        return $dataSet[DefineIsNewProductStep::DATA_KEY_IS_NEW_PRODUCT];
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
