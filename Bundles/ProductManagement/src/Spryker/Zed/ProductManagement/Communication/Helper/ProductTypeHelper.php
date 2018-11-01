<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Helper;

use Exception;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\Product\Persistence\ProductQueryContainerInterface;

class ProductTypeHelper implements ProductTypeHelperInterface
{
    /**
     * @var \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface
     */
    protected $productQueryQueryContainer;

    /**
     * @param \Spryker\Zed\Product\Persistence\ProductQueryContainerInterface $productQueryContainer
     */
    public function __construct(ProductQueryContainerInterface $productQueryContainer)
    {
        $this->productQueryQueryContainer = $productQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    public function isProductBundleByProductAbstract(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAbstractTransfer->requireSku();

        $productAbstractEntity = $this->getProductAbstractEntity($productAbstractTransfer->getSku());
        return $this->isProductBundleByProductAbstractEntity($productAbstractEntity);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return bool
     */
    public function isProductBundleByProductAbstractEntity(SpyProductAbstract $productAbstractEntity)
    {
        foreach ($productAbstractEntity->getSpyProducts() as $productEntity) {
            if ($productEntity->getSpyProductBundlesRelatedByFkProduct()->count() > 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    public function isGiftCardByProductAbstractTransfer(ProductAbstractTransfer $productAbstractTransfer)
    {
        $productAbstractTransfer->requireSku();

        $productAbstractEntity = $this->getProductAbstractEntity($productAbstractTransfer->getSku());
        return $this->isGiftCardByProductAbstractEntity($productAbstractEntity);
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductAbstract $productAbstractEntity
     *
     * @return bool
     */
    public function isGiftCardByProductAbstractEntity(SpyProductAbstract $productAbstractEntity)
    {
        if (!method_exists($productAbstractEntity, 'getSpyGiftCardProductAbstractConfigurationLinks')) {
            return false;
        }

        if (!$productAbstractEntity->getSpyGiftCardProductAbstractConfigurationLinks()->getFirst()) {
            return false;
        }

        return true;
    }

    /**
     * @param string $skuProductAbstract
     *
     * @throws \Exception
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstract
     */
    protected function getProductAbstractEntity($skuProductAbstract)
    {
        $productAbstractEntity = $this->productQueryQueryContainer
            ->queryProductAbstractBySku($skuProductAbstract)
            ->findOne();

        if ($productAbstractEntity === null) {
            throw new Exception('Product abstract not found');
        }

        return $productAbstractEntity;
    }
}
