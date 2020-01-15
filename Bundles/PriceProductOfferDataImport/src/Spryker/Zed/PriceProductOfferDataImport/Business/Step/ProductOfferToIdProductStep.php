<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Business\Step;

use Orm\Zed\Product\Persistence\Map\SpyProductTableMap;
use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductOfferDataImport\Business\DataSet\PriceProductOfferDataSetInterface;

class ProductOfferToIdProductStep implements DataImportStepInterface
{
    /**
     * @var array
     */
    protected $idProductCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[PriceProductOfferDataSetInterface::CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                '"%s" must be in the data set. Given: "%s"',
                PriceProductOfferDataSetInterface::CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        $productConcreteSku = $dataSet[PriceProductOfferDataSetInterface::CONCRETE_SKU];
        if (!empty($productConcreteSku)) {
            $dataSet[PriceProductOfferDataSetInterface::ID_PRODUCT_CONCRETE] = $this->resolveIdProductByConcreteSku($productConcreteSku);
        }
    }

    /**
     * @param string $sku
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function resolveIdProductByConcreteSku(string $sku): int
    {
        if (!isset($this->idProductCache[$sku])) {
            $productQuery = SpyProductQuery::create();
            $productQuery->select(SpyProductTableMap::COL_ID_PRODUCT);
            $idProduct = $productQuery->findOneBySku($sku);

            if (!$idProduct) {
                throw new EntityNotFoundException(sprintf('Concrete product by sku "%s" not found.', $sku));
            }

            $this->idProductCache[$sku] = $idProduct;
        }

        return $this->idProductCache[$sku];
    }

    /**
     * @param string $sku
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function resolveIdProductByAbstractSku(string $sku): int
    {
        if (!isset($this->idProductCache[$sku])) {
            $productAbstractEntity = SpyProductAbstractQuery::create()
                ->findOneBySku($sku);

            if (!$productAbstractEntity) {
                throw new EntityNotFoundException(sprintf('Abstract product by sku "%s" not found.', $sku));
            }

            $this->idProductCache[$sku] = $productAbstractEntity->getIdProductAbstract();
        }

        return $this->idProductCache[$sku];
    }
}
