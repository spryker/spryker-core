<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOfferStorage\Business\ProductConcreteOffersStorage;

use Generated\Shared\Transfer\ProductConcreteProductOffersStorageTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface;
use Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface;

class ProductConcreteOffersStorageWriter implements ProductConcreteOffersStorageWriterInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface
     */
    protected $merchantProductOfferStorageEntityManager;

    /**
     * @param \Spryker\Zed\MerchantProductOfferStorage\Dependency\Facade\MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOfferStorage\Persistence\MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
     */
    public function __construct(
        MerchantProductOfferStorageToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferStorageEntityManagerInterface $merchantProductOfferStorageEntityManager
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantProductOfferStorageEntityManager = $merchantProductOfferStorageEntityManager;
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

        $productOffersGroupedBySku = $this->getGroupedProductOfferByConcreteSku($productOfferCollectionTransfer);

        foreach ($productOffersGroupedBySku as $sku => $productOfferReferenceList) {
            $productConcreteProductOffersStorageTransfer = new ProductConcreteProductOffersStorageTransfer();
            $productConcreteProductOffersStorageTransfer->setConcreteSku($sku);
            $productConcreteProductOffersStorageTransfer->setData($productOfferReferenceList);

            $this->merchantProductOfferStorageEntityManager->saveProductConcreteProductOffersStorage($productConcreteProductOffersStorageTransfer);
        }
    }

    /**
     * @param string[] $concreteSkus
     *
     * @return void
     */
    public function unpublish(array $concreteSkus): void
    {
        $this->merchantProductOfferStorageEntityManager->deleteProductConcreteProductOffersStorage($concreteSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array
     */
    protected function getGroupedProductOfferByConcreteSku(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
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
