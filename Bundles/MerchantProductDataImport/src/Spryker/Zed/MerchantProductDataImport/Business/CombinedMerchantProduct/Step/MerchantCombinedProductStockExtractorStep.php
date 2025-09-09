<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Step;

use Generated\Shared\Transfer\ErrorTransfer;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\DataSet\MerchantCombinedProductDataSetInterface;
use Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException;

class MerchantCombinedProductStockExtractorStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    public const KEY_PRODUCT_STOCKS = 'PRODUCT_STOCKS';

    /**
     * @var string
     */
    public const KEY_WAREHOUSE_NAME = 'warehouse_name';

    /**
     * @var string
     */
    public const KEY_QUANTITY = 'quantity';

    /**
     * @var string
     */
    public const KEY_IS_NEVER_OUT_OF_STOCK = 'is_never_out_of_stock';

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $merchantStocks = $dataSet[AddMerchantStockStep::KEY_MERCHANT_STOCKS];

        /** @var array<string> $merchantWarehouseNames */
        $merchantWarehouseNames = array_keys($merchantStocks);

        $this->assertMerchantOwnsWarehouse($dataSet, $merchantWarehouseNames);

        $productStocks = [];
        foreach ($merchantWarehouseNames as $warehouseName) {
            if (
                !$this->productStockKeyExists($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_STOCK_WAREHOUSE_IS_NEVER_OUT_OF_STOCK, $warehouseName)
                || !$this->productStockKeyExists($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_STOCK_WAREHOUSE_QUANTITY, $warehouseName)
            ) {
                continue;
            }

            $productStocks[] = $this->collectProductStocksByWarehouseName($dataSet, $warehouseName);
        }

        $dataSet[static::KEY_PRODUCT_STOCKS] = $productStocks;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $warehouseName
     *
     * @return array<string, mixed>
     */
    protected function collectProductStocksByWarehouseName(DataSetInterface $dataSet, string $warehouseName): array
    {
        $warehouseQuantityKey = $this->buildProductStockKey(
            MerchantCombinedProductDataSetInterface::KEY_PRODUCT_STOCK_WAREHOUSE_QUANTITY,
            $warehouseName,
        );

        $this->assertDataSetValueIsNumeric($dataSet, $warehouseQuantityKey);

        return [
            static::KEY_WAREHOUSE_NAME => $warehouseName,
            static::KEY_QUANTITY => $this->getProductStockValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_STOCK_WAREHOUSE_QUANTITY, $warehouseName),
            static::KEY_IS_NEVER_OUT_OF_STOCK => $this->getProductStockValue($dataSet, MerchantCombinedProductDataSetInterface::KEY_PRODUCT_STOCK_WAREHOUSE_IS_NEVER_OUT_OF_STOCK, $warehouseName),
        ];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $keyPattern
     * @param string $warehouseName
     *
     * @return mixed
     */
    protected function getProductStockValue(
        DataSetInterface $dataSet,
        string $keyPattern,
        string $warehouseName
    ): mixed {
        $key = $this->buildProductStockKey($keyPattern, $warehouseName);

        if (!isset($dataSet[$key])) {
            return null;
        }

        return $dataSet[$key];
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $keyPattern
     * @param string $warehouseName
     *
     * @return bool
     */
    protected function productStockKeyExists(
        DataSetInterface $dataSet,
        string $keyPattern,
        string $warehouseName
    ): bool {
        $key = $this->buildProductStockKey($keyPattern, $warehouseName);

        return isset($dataSet[$key]);
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param string $key
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function assertDataSetValueIsNumeric(DataSetInterface $dataSet, string $key): void
    {
        if (!$dataSet[$key] || is_numeric($dataSet[$key])) {
            return;
        }

        throw MerchantCombinedProductException::createWithError(
            (new ErrorTransfer())
                ->setMessage('Product stock value for key "%s1%" must be numeric. Provided value: "%s2%".')
                ->setParameters(['%s1%' => $key, '%s2%' => $dataSet[$key]]),
        );
    }

    /**
     * @param string $keyPattern
     * @param string $warehouseName
     *
     * @return string
     */
    protected function buildProductStockKey(string $keyPattern, string $warehouseName): string
    {
        return strtr(
            $keyPattern,
            [MerchantCombinedProductDataSetInterface::PLACEHOLDER_WAREHOUSE_NAME => $warehouseName],
        );
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     * @param array<string> $merchantWarehouseNames
     *
     * @throws \Spryker\Zed\MerchantProductDataImport\Business\CombinedMerchantProduct\Exception\MerchantCombinedProductException
     *
     * @return void
     */
    protected function assertMerchantOwnsWarehouse(DataSetInterface $dataSet, array $merchantWarehouseNames): void
    {
        $dataSetKeys = array_keys($dataSet->getArrayCopy());
        $productStockHeaderRegex = $this->buildProductStockHeaderRegex();

        $dataSetWarehouseNames = [];
        foreach ($dataSetKeys as $header) {
            if (!preg_match($productStockHeaderRegex, $header, $matches)) {
                continue;
            }

            $dataSetWarehouseNames[] = $matches[static::KEY_WAREHOUSE_NAME];
        }

        $dataSetWarehouseNames = array_unique($dataSetWarehouseNames);

        if (!$dataSetWarehouseNames) {
            return;
        }

        $notOwnedWarehouseNames = array_diff($dataSetWarehouseNames, $merchantWarehouseNames);

        if ($notOwnedWarehouseNames) {
            throw MerchantCombinedProductException::createWithError(
                (new ErrorTransfer())->setMessage('Warehouses can only be accessed by the merchants who own them.'),
            );
        }
    }

    /**
     * @return string
     */
    protected function buildProductStockHeaderRegex(): string
    {
        $regexBase = strtr(MerchantCombinedProductDataSetInterface::KEY_PRODUCT_STOCK_WAREHOUSE_PROPERTY, [
            MerchantCombinedProductDataSetInterface::PLACEHOLDER_WAREHOUSE_NAME => sprintf('(?<%s>[\w\s-]+)', static::KEY_WAREHOUSE_NAME),
            MerchantCombinedProductDataSetInterface::PLACEHOLDER_PROPERTY => '([\w-]+)',
        ]);

        return sprintf('/^%s$/', str_replace('.', '\.', $regexBase));
    }
}
