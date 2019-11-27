<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductOfferStorage;

use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductOfferStorageQuery;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;

class ProductOfferStorageWriter implements ProductOfferStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var array
     */
    protected $merchantProductOfferPublishPreCheckPlugins;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param array $merchantProductOfferPublishPreCheckPlugins
     */
    public function __construct(
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        array $merchantProductOfferPublishPreCheckPlugins
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantProductOfferPublishPreCheckPlugins = $merchantProductOfferPublishPreCheckPlugins;
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function publish(array $productOfferReferences): void
    {
        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setProductOfferReferences($productOfferReferences);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $productOfferStorageEntity = SpyProductOfferStorageQuery::create()
                ->filterByProductOfferReference($productOfferTransfer->getProductOfferReference())
                ->findOneOrCreate();
            $productOfferStorageEntity->setData($this->createProductOfferStorageTransfer($productOfferTransfer)->modifiedToArray());

            if ($this->executeMerchantProductOfferPublishPreCheckPlugins($productOfferTransfer)) {
                $productOfferStorageEntity->save();
            } elseif (!$productOfferStorageEntity->isPrimaryKeyNull()) {
                $productOfferStorageEntity->delete();
            }
        }
    }

    /**
     * @param string[] $productOfferReferences
     *
     * @return void
     */
    public function unpublish(array $productOfferReferences): void
    {
        $productOfferStorageEntities = SpyProductOfferStorageQuery::create()->filterByProductOfferReference_In($productOfferReferences)->find();

        foreach ($productOfferStorageEntities as $productOfferStorageEntity) {
            $productOfferStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer
     */
    protected function createProductOfferStorageTransfer(ProductOfferTransfer $productOfferTransfer): ProductOfferStorageTransfer
    {
        $productOfferStorageTransfer = new ProductOfferStorageTransfer();

        $productOfferStorageTransfer->setIdProductOffer($productOfferTransfer->getIdProductOffer());
        $productOfferStorageTransfer->setIdMerchant($productOfferTransfer->getFkMerchant());
        $productOfferStorageTransfer->setProductOfferReference($productOfferTransfer->getProductOfferReference());

        return $productOfferStorageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return bool
     */
    protected function executeMerchantProductOfferPublishPreCheckPlugins(ProductOfferTransfer $productOfferTransfer): bool
    {
        /** @var \Spryker\Zed\MerchantProductofferStorageExtension\Dependency\Plugin\MerchantProductOfferPublishPreCheckPluginInterface $merchantProductOfferPublishPreCheckPlugin */
        foreach ($this->merchantProductOfferPublishPreCheckPlugins as $merchantProductOfferPublishPreCheckPlugin) {
            if (!$merchantProductOfferPublishPreCheckPlugin->isValid($productOfferTransfer)) {
                return false;
            }
        }

        return true;
    }
}
