<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\InactiveOfferItemsFilter;

use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class InactiveOfferItemsFilter implements InactiveOfferItemsFilterInterface
{
    protected const MESSAGE_PARAM_SKU = '%sku%';
    protected const MESSAGE_INFO_OFFER_INACTIVE_PRODUCT_REMOVED = 'product-offer.info.product-offer-inactive.removed';

    /**
     * @var \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface
     */
    protected $productOfferRepository;

    /**
     * @var \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @var \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface
     */
    protected $messengerFacade;

    /**
     * @param \Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface $productOfferRepository
     * @param \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface $messengerFacade
     */
    public function __construct(
        ProductOfferRepositoryInterface $productOfferRepository,
        ProductOfferToStoreFacadeInterface $storeFacade,
        ProductOfferToMessengerFacadeInterface $messengerFacade
    ) {
        $this->productOfferRepository = $productOfferRepository;
        $this->storeFacade = $storeFacade;
        $this->messengerFacade = $messengerFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveOfferItems(QuoteTransfer $quoteTransfer): QuoteTransfer
    {
        $offerReferences = $this->getProductOfferReferencesFromQuoteTransfer($quoteTransfer);

        $productOfferCollectionTransfer = new ProductOfferCriteriaFilterTransfer();
        $productOfferCollectionTransfer->setProductOfferReferences($offerReferences);
        $productOfferCollectionTransfer->setIsActive(true);
        $productOfferCollectionTransfer->setIdStore(
            $this->storeFacade->getCurrentStore()->getIdStore()
        );
        $productOfferCollectionTransfer = $this->productOfferRepository->find($productOfferCollectionTransfer);

        $indexedProductConcreteTransfers = $this->indexProductOfferTransfersByOfferReferences($productOfferCollectionTransfer);

        foreach ($quoteTransfer->getItems() as $key => $itemTransfer) {
            if (
                $itemTransfer->getProductOfferReference() !== null &&
                !$this->existOffer($itemTransfer->getProductOfferReference(), $indexedProductConcreteTransfers)
            ) {
                $quoteTransfer->getItems()->offsetUnset($key);
                $this->addFilterMessage($itemTransfer->getSku());
            }
        }

        return $quoteTransfer;
    }

    /**
     * @param string $offerReference
     * @param array $indexedProductConcreteTransfers
     *
     * @return bool
     */
    protected function existOffer(string $offerReference, array $indexedProductConcreteTransfers): bool
    {
        if (!isset($indexedProductConcreteTransfers[$offerReference])) {
            return false;
        }

        return true;
    }

    /**
     * @param string $sku
     *
     * @return void
     */
    protected function addFilterMessage(string $sku): void
    {
        $messageTransfer = new MessageTransfer();
        $messageTransfer->setValue(static::MESSAGE_INFO_OFFER_INACTIVE_PRODUCT_REMOVED);
        $messageTransfer->setParameters([
            static::MESSAGE_PARAM_SKU => $sku,
        ]);

        $this->messengerFacade->addInfoMessage($messageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return string[]
     */
    protected function getProductOfferReferencesFromQuoteTransfer(QuoteTransfer $quoteTransfer): array
    {
        $offerReferences = [];

        foreach ($quoteTransfer->getItems() as $itemTransfer) {
            if ($itemTransfer->getProductOfferReference()) {
                $offerReferences[] = $itemTransfer->getProductOfferReference();
            }
        }

        return $offerReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer[]
     */
    protected function indexProductOfferTransfersByOfferReferences(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $indexedProductOfferTransfers = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $indexedProductOfferTransfers[$productOfferTransfer->getProductOfferReference()] = $productOfferTransfer;
        }

        return $indexedProductOfferTransfers;
    }
}
