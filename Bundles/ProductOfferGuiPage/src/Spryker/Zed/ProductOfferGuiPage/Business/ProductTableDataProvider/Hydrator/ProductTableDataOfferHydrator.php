<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGuiPage\Business\ProductTableDataProvider\Hydrator;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\ProductTableCriteriaTransfer;
use Generated\Shared\Transfer\ProductTableDataTransfer;
use Generated\Shared\Transfer\ProductTableRowDataTransfer;
use Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductOfferFacadeInterface;

class ProductTableDataOfferHydrator implements ProductTableDataHydratorInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferGuiPage\Dependency\Facade\ProductOfferGuiPageToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(ProductOfferGuiPageToProductOfferFacadeInterface $productOfferFacade)
    {
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * Hydrates concrete product transfers from the collection with offer data.
     *
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\ProductTableCriteriaTransfer $productTableCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    public function hydrateProductTableData(
        ProductTableDataTransfer $productTableDataTransfer,
        ProductTableCriteriaTransfer $productTableCriteriaTransfer
    ): ProductTableDataTransfer {
        $productConcreteSkus = $this->extractProductConcreteSkus($productTableDataTransfer);
        $merchantId = $productTableCriteriaTransfer
            ->requireMerchantUser()
            ->getMerchantUser()
            ->requireIdMerchant()
            ->getIdMerchant();
        $productOfferCriteriaFilterTransfer = (new ProductOfferCriteriaFilterTransfer())
            ->setConcreteSkus($productConcreteSkus)
            ->setIdMerchant($merchantId);

        $productOfferCollectionTransfer = $this->productOfferFacade->find($productOfferCriteriaFilterTransfer);

        return $this->calculateProductOffersPerProductConcrete($productTableDataTransfer, $productOfferCollectionTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productConcreteDataCollectionTransfer
     *
     * @return string[]
     */
    protected function extractProductConcreteSkus(ProductTableDataTransfer $productConcreteDataCollectionTransfer): array
    {
        $productConcreteIds = array_map(function (ProductTableRowDataTransfer $productTableRowDataTransfer): ?string {
            return $productTableRowDataTransfer->getSku() ?? null;
        }, $productConcreteDataCollectionTransfer->getRows()->getArrayCopy());

        $productConcreteIds = array_filter($productConcreteIds);

        return $productConcreteIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductTableDataTransfer $productTableDataTransfer
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductTableDataTransfer
     */
    protected function calculateProductOffersPerProductConcrete(
        ProductTableDataTransfer $productTableDataTransfer,
        ProductOfferCollectionTransfer $productOfferCollectionTransfer
    ): ProductTableDataTransfer {
        foreach ($productTableDataTransfer->getRows() as $productTableRowDataTransfer) {
            $offersCount = 0;

            foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
                if ($productOfferTransfer->getConcreteSku() === $productTableRowDataTransfer->getSku()) {
                    ++$offersCount;
                }
            }

            $productTableRowDataTransfer->setOffersCount($offersCount);
        }

        return $productTableDataTransfer;
    }
}
