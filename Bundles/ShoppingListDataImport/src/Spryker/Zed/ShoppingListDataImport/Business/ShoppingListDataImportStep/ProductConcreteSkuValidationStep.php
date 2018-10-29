<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ShoppingListDataImport\Business\ShoppingListDataImportStep;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ShoppingListDataImport\Business\DataSet\ShoppingListItemDataSetInterface;

class ProductConcreteSkuValidationStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $productConcreteSkuCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $sku = $dataSet[ShoppingListItemDataSetInterface::COLUMN_PRODUCT_SKU];
        if (!isset($this->productConcreteSkuCache[$sku])) {
            $productQuery = SpyProductQuery::create();
            $idProduct = $productQuery
                ->select([SpyProductTableMap::COL_ID_PRODUCT])
                ->findOneBySku($sku);

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Could not find product concrete by SKU "%s"', $sku));
            }

            $this->productConcreteSkuCache[$sku] = $idProduct;
        }
    }
}
