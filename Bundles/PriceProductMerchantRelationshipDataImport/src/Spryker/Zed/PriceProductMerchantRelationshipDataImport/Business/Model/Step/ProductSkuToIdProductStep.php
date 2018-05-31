<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\Step;

use Orm\Zed\Product\Persistence\SpyProductAbstractQuery;
use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\DataImport\Business\Exception\DataKeyNotFoundInDataSetException;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductMerchantRelationshipDataImport\Business\Model\DataSet\PriceProductMerchantRelationshipDataSet;

class ProductSkuToIdProductStep implements DataImportStepInterface
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
        if (empty($dataSet[PriceProductMerchantRelationshipDataSet::ABSTRACT_SKU]) && empty($dataSet[PriceProductMerchantRelationshipDataSet::CONCRETE_SKU])) {
            throw new DataKeyNotFoundInDataSetException(sprintf(
                'One of "%s" or "%s" must be in the data set. Given: "%s"',
                PriceProductMerchantRelationshipDataSet::ABSTRACT_SKU,
                PriceProductMerchantRelationshipDataSet::CONCRETE_SKU,
                implode(', ', array_keys($dataSet->getArrayCopy()))
            ));
        }

        $productAbstractSku = $dataSet[PriceProductMerchantRelationshipDataSet::ABSTRACT_SKU];
        if (!empty($productAbstractSku)) {
            $dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_ABSTRACT] = $this->resolveIdProductByAbstractSku($productAbstractSku);
        }

        $productConcreteSku = $dataSet[PriceProductMerchantRelationshipDataSet::CONCRETE_SKU];
        if (!empty($productConcreteSku)) {
            $dataSet[PriceProductMerchantRelationshipDataSet::ID_PRODUCT_CONCRETE] = $this->resolveIdProductByConcreteSku($productConcreteSku);
        }
    }

    /**
     * @param string $sku
     *
     * @throws \Pyz\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function resolveIdProductByConcreteSku($sku)
    {
        if (!isset($this->idProductCache[$sku])) {
            $productEntity = SpyProductQuery::create()
                ->findOneBySku($sku);

            if (!$productEntity) {
                throw new EntityNotFoundException(sprintf('Concrete product by sku "%s" not found.', $sku));
            }

            $this->idProductCache[$sku] = $productEntity->getIdProduct();
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
    protected function resolveIdProductByAbstractSku($sku)
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
