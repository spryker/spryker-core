<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\Model\DataSet\ProductAlternativeDataSetInterface;

class ProductSkuToProductIdStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idProductCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $productSku = $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_CONCRETE_SKU];

        if (!isset($this->idProductCache[$productSku])) {
            $idProduct = SpyProductQuery::create()->findOneBySku($productSku)->getIdProduct();

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Could not find product by sku "%s"', $productSku));
            }

            $this->idProductCache[$productSku] = $idProduct;
        }

        $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_PRODUCT_ID] = $this->idProductCache[$productSku];
    }
}
