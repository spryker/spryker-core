<?php

/**
 * MIT License
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ProductListDataImport\Business\Model\DataSet\ProductListDataSetInterface;

class ProductConcreteSkuToIdProductConcreteStep implements DataImportStepInterface
{
    /**
     * @var int[]
     */
    protected $idProductConcreteCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $productConcreteSku = $dataSet[ProductListDataSetInterface::CONCRETE_SKU];
        if (!$productConcreteSku) {
            throw new InvalidDataException(sprintf('"%s" is required.', ProductListDataSetInterface::CONCRETE_SKU));
        }

        $dataSet[ProductListDataSetInterface::ID_PRODUCT_CONCRETE] = $this->getIdProductConcreteBySku($productConcreteSku);
    }

    /**
     * @param string $productConcreteSku
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdProductConcreteBySku(string $productConcreteSku): int
    {
        if (!isset($this->idProductConcreteCache[$productConcreteSku])) {
            $idProductConcrete = SpyProductQuery::create()
                ->select(SpyProductTableMap::COL_ID_PRODUCT)
                ->findOneBySku($productConcreteSku);

            if (!$idProductConcrete) {
                throw new EntityNotFoundException(sprintf('Could not find Product Concrete by sku "%s"', $productConcreteSku));
            }
            $this->idProductConcreteCache[$productConcreteSku] = $idProductConcrete;
        }

        return $this->idProductConcreteCache[$productConcreteSku];
    }
}
