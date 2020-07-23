<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Communication\Helper;

use Exception;
use Generated\Shared\Transfer\ProductAbstractTransfer;
use Orm\Zed\Product\Persistence\SpyProductAbstract;
use Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface;

class ProductTypeHelper implements ProductTypeHelperInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @param \Spryker\Zed\ProductOfferGui\Persistence\ProductOfferGuiRepositoryInterface $productOfferRepository
     */
    public function __construct(ProductOfferGuiRepositoryInterface $productOfferRepository)
    {
        $this->productOfferRepository = $productOfferRepository;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductAbstractTransfer $productAbstractTransfer
     *
     * @return bool
     */
    public function isProductBundleByProductAbstract(ProductAbstractTransfer $productAbstractTransfer): bool
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
    protected function isProductBundleByProductAbstractEntity(SpyProductAbstract $productAbstractEntity): bool
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
    public function isGiftCardByProductAbstract(ProductAbstractTransfer $productAbstractTransfer): bool
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
    public function isGiftCardByProductAbstractEntity(SpyProductAbstract $productAbstractEntity): bool
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
    protected function getProductAbstractEntity($skuProductAbstract): SpyProductAbstract
    {
        $productAbstractEntity = $this->productOfferRepository
            ->findProductAbstractBySku($skuProductAbstract);

        if (!$productAbstractEntity) {
            throw new Exception('Product abstract not found');
        }

        return $productAbstractEntity;
    }
}
