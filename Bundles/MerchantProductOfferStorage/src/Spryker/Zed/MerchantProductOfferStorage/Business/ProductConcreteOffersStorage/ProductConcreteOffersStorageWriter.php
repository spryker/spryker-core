<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    /**
     * @uses \Spryker\Zed\ProductOffer\ProductOfferConfig::STATUS_APPROVED
     */
    public const STATUS_APPROVED = 'approved';

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
     * @param string[] $productSkus
     *
     * @return void
     */
    public function publish(array $productSkus): void
    {
        $productSkus = array_unique($productSkus);
        $productOfferCriteriaFilterTransfer = $this->createProductOfferCriteriaFilterTransfer($productSkus);
        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        $productOffersGroupedBySku = $this->groupProductOfferByConcreteSku($productOfferCollectionTransfer);

        $storedProductSkus = [];
        foreach ($productOffersGroupedBySku as $sku => $productOfferReferenceList) {
            $productConcreteProductOffersStorageEntity = $this->createProductConcreteProductOffersStoragePropelQuery()
                ->filterByConcreteSku($sku)
                ->findOneOrCreate();
            $productConcreteProductOffersStorageEntity->setData($productOfferReferenceList);

            $productConcreteProductOffersStorageEntity->save();

            $storedProductSkus[] = $sku;
        }

        $productSkusToDelete = array_diff($productSkus, $storedProductSkus);

        if (!empty($productSkusToDelete)) {
            $productConcreteProductOffersStorageEntities = $this->createProductConcreteProductOffersStoragePropelQuery()
                ->filterByConcreteSku_In($productSkusToDelete)
                ->find();

            foreach ($productConcreteProductOffersStorageEntities as $productConcreteProductOffersStorageEntity) {
                $productConcreteProductOffersStorageEntity->delete();
            }
        }
    }

    /**
     * @param string[] $productSkus
     *
     * @return void
     */
    public function unpublish(array $productSkus): void
    {
        $productConcreteProductOffersStorageEntities = SpyProductConcreteProductOffersStorageQuery::create()
            ->filterByConcreteSku_In($productSkus)
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

    /**
     * @param string[] $productSkus
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer
     */
    protected function createProductOfferCriteriaFilterTransfer(array $productSkus): ProductOfferCriteriaFilterTransfer
    {
        return (new ProductOfferCriteriaFilterTransfer())
            ->setConcreteSkus($productSkus)
            ->setIsActive(true)
            ->setIsActiveConcreteProduct(true)
            ->addApprovalStatus(static::STATUS_APPROVED);
    }

    /**
     * @return \Orm\Zed\MerchantProductOfferStorage\Persistence\SpyProductConcreteProductOffersStorageQuery
     */
    protected function createProductConcreteProductOffersStoragePropelQuery(): SpyProductConcreteProductOffersStorageQuery
    {
        return SpyProductConcreteProductOffersStorageQuery::create();
    }
}
