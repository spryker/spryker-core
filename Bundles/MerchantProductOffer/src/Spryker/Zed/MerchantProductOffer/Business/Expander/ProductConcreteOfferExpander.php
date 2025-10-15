<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProductOffer\Business\Expander;

use ArrayObject;
use Generated\Shared\Transfer\MerchantCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface;
use Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface;

class ProductConcreteOfferExpander implements ProductConcreteOfferExpanderInterface
{
    /**
     * @var \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface
     */
    protected $productOfferFacade;

    /**
     * @var \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface
     */
    protected $merchantFacade;

    /**
     * @param \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\MerchantProductOffer\Dependency\Facade\MerchantProductOfferToMerchantFacadeInterface $merchantFacade
     */
    public function __construct(
        MerchantProductOfferToProductOfferFacadeInterface $productOfferFacade,
        MerchantProductOfferToMerchantFacadeInterface $merchantFacade
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->merchantFacade = $merchantFacade;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductConcreteTransfer>
     */
    public function expandProductConcretesWithOffers(array $productConcreteTransfers): array
    {
        $offersByProductConcreteIds = $this->getOffersByProductConcreteIds($productConcreteTransfers);

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            if (empty($offersByProductConcreteIds[$productConcreteTransfer->getIdProductConcrete()])) {
                continue;
            }

            $productConcreteTransfer->setOffers(new ArrayObject(
                $offersByProductConcreteIds[$productConcreteTransfer->getIdProductConcrete()],
            ));
        }

        return $productConcreteTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductConcreteTransfer> $productConcreteTransfers
     *
     * @return array<int, array<int, \Generated\Shared\Transfer\ProductOfferTransfer>>
     */
    protected function getOffersByProductConcreteIds(array $productConcreteTransfers): array
    {
        $result = [];
        $productConcreteSkus = [];

        foreach ($productConcreteTransfers as $productConcreteTransfer) {
            $productConcreteSkus[] = $productConcreteTransfer->getSku();
        }

        $productOfferCriteriaTransfer = $this->createProductOfferCriteriaTransfer(array_unique($productConcreteSkus));
        $productOfferCollection = $this->productOfferFacade->get($productOfferCriteriaTransfer);
        $productOfferCollection = $this->expandProductOffersWithMerchantName($productOfferCollection);

        foreach ($productOfferCollection->getProductOffers() as $productOfferTransfer) {
            $result[(int)$productOfferTransfer->getIdProductConcrete()][] = $productOfferTransfer;
        }

        return $result;
    }

    /**
     * @param array $productConcreteSkus
     *
     * @return \Generated\Shared\Transfer\ProductOfferCriteriaTransfer
     */
    protected function createProductOfferCriteriaTransfer(array $productConcreteSkus): ProductOfferCriteriaTransfer
    {
        return (new ProductOfferCriteriaTransfer())
            ->setConcreteSkus($productConcreteSkus);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollection
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    protected function expandProductOffersWithMerchantName(
        ProductOfferCollectionTransfer $productOfferCollection
    ): ProductOfferCollectionTransfer {
        $merchantCriteriaTransfer = $this->createMerchantCriteriaTransfer($productOfferCollection);
        $merchantCollection = $this->merchantFacade->get($merchantCriteriaTransfer);
        $merchantsByMerchantReference = $this->getMerchantsByMerchantReference(
            $merchantCollection->getMerchants()->getArrayCopy(),
        );

        foreach ($productOfferCollection->getProductOffers() as $productOfferTransfer) {
            if (!isset($merchantsByMerchantReference[$productOfferTransfer->getMerchantReference()])) {
                continue;
            }

            $productOfferTransfer->setMerchantName(
                $merchantsByMerchantReference[$productOfferTransfer->getMerchantReference()]->getName(),
            );
        }

        return $productOfferCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollection
     *
     * @return \Generated\Shared\Transfer\MerchantCriteriaTransfer
     */
    protected function createMerchantCriteriaTransfer(
        ProductOfferCollectionTransfer $productOfferCollection
    ): MerchantCriteriaTransfer {
        $merchantReferences = [];

        foreach ($productOfferCollection->getProductOffers()->getArrayCopy() as $productOfferTransfer) {
            $merchantReferences[] = $productOfferTransfer->getMerchantReference();
        }

        return (new MerchantCriteriaTransfer())
            ->setMerchantReferences(array_unique(array_filter($merchantReferences)))
            ->setWithExpanders(false);
    }

    /**
     * @param array<\Generated\Shared\Transfer\MerchantTransfer> $merchantTransfers
     *
     * @return array<\Generated\Shared\Transfer\MerchantTransfer>
     */
    protected function getMerchantsByMerchantReference(array $merchantTransfers): array
    {
        $merchantsByMerchantReference = [];

        foreach ($merchantTransfers as $merchantTransfer) {
            $merchantsByMerchantReference[$merchantTransfer->getMerchantReference()] = $merchantTransfer;
        }

        return $merchantsByMerchantReference;
    }
}
