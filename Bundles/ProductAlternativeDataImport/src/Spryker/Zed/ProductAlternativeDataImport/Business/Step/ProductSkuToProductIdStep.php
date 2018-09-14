<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ProductAlternativeDataImport\Business\Step;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductAlternativeDataImport\Business\ProductAlternativeDataSet\ProductAlternativeDataSetInterface;

class ProductSkuToProductIdStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idProductCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productSku = $dataSet[ProductAlternativeDataSetInterface::KEY_COLUMN_CONCRETE_SKU];

        if (!isset($this->idProductCache[$productSku])) {
            $productEntity = SpyProductQuery::create()->findOneBySku($productSku);
            if (!$productEntity) {
                throw new EntityNotFoundException(sprintf('Could not find product by sku "%s"', $productSku));
            }

            $this->idProductCache[$productSku] = $productEntity->getIdProduct();
        }

        $dataSet[ProductAlternativeDataSetInterface::FK_PRODUCT] = $this->idProductCache[$productSku];
    }
}
