<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
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
            if (!isset($productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()])) {
                $productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()] = [];
            }
            $productOffersGroupedBySku[$productOfferTransfer->getConcreteSku()][] = strtolower($productOfferTransfer->getProductOfferReference());
        }

        return $productOffersGroupedBySku;
    }
}
