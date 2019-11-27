<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
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
     * @param string[] $concreteSkus
     *
     * @return void
     */
    public function publish(array $concreteSkus): void
    {
        $concreteSkus = array_unique($concreteSkus);
        $productOfferCriteriaFilterTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCriteriaFilterTransfer->setConcreteSkus($concreteSkus);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        $productOffersGroupedBySku = $this->groupProductOfferByConcreteSku($productOfferCollectionTransfer);

        foreach ($productOffersGroupedBySku as $sku => $productOfferReferenceList) {
            $productConcreteProductOffersStorageEntity = SpyProductConcreteProductOffersStorageQuery::create()
                ->filterByConcreteSku($sku)
                ->findOneOrCreate();
            $productConcreteProductOffersStorageEntity->setData($productOfferReferenceList);

            $productConcreteProductOffersStorageEntity->save();
        }
    }

    /**
     * @param string[] $concreteSkus
     *
     * @return void
     */
    public function unpublish(array $concreteSkus): void
    {
        $productConcreteProductOffersStorageEntities = SpyProductConcreteProductOffersStorageQuery::create()
            ->filterByConcreteSku_In($concreteSkus)
            ->find();

        foreach ($productConcreteProductOffersStorageEntities as $productConcreteProductOffersStorageEntity) {
            $productConcreteProductOffersStorageEntity->delete();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array
     */
    protected function groupProductOfferByConcreteSku(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $productOffersGroupedBySku = [];
        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if (!$this->executeMerchantProductOfferPublishPreCheckPlugins($productOfferTransfer)) {
                continue;
            }
            if (!isset($productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()])) {
                $productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()] = [];
            }
            $productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()][] = strtolower($productOfferTransfer->getProductOfferReference());
        }

        return $productOffersGroupedBySku;
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
