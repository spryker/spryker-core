<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOffer\Business\InactiveProductOfferItemsFilter;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Shared\ProductOffer\ProductOfferConfig;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToMessengerFacadeInterface;
use Spryker\Zed\ProductOffer\Dependency\Facade\ProductOfferToStoreFacadeInterface;
use Spryker\Zed\ProductOffer\Persistence\ProductOfferRepositoryInterface;

class InactiveProductOfferItemsFilter implements InactiveProductOfferItemsFilterInterface
{
    /**
     * @var string
     */
    protected const MESSAGE_PARAM_SKU = '%sku%';

    /**
     * @var string
     */
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
     * @param list<string> $itemProductOfferReferencesToSkipFiltering
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    public function filterInactiveProductOfferItems(QuoteTransfer $quoteTransfer, array $itemProductOfferReferencesToSkipFiltering = []): QuoteTransfer
    {
        $filteredItemTransfers = $this->getFilteredProductOfferItems(
            $quoteTransfer->getItems(),
            $quoteTransfer->getStore(),
            $itemProductOfferReferencesToSkipFiltering,
        );

        return $quoteTransfer->setItems($filteredItemTransfers);
    }

    /**
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\CartChangeTransfer
     */
    public function filterOutInactiveCartChangeProductOfferItems(
        CartChangeTransfer $cartChangeTransfer
    ): CartChangeTransfer {
        $filteredItemTransfers = $this->getFilteredProductOfferItems(
            $cartChangeTransfer->getItems(),
            $cartChangeTransfer->getQuoteOrFail()->getStoreOrFail(),
        );

        return $cartChangeTransfer->setItems($filteredItemTransfers);
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     * @param list<string> $itemProductOfferReferencesToSkipFiltering
     *
     * @return \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer>
     */
    protected function getFilteredProductOfferItems(
        ArrayObject $itemTransfers,
        StoreTransfer $storeTransfer,
        array $itemProductOfferReferencesToSkipFiltering = []
    ): ArrayObject {
        $productOfferReferences = $this->getProductOfferReferencesFromItems($itemTransfers);

        if (!$productOfferReferences) {
            return $itemTransfers;
        }

        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferReferences($productOfferReferences)
            ->setIsActive(true)
            ->setApprovalStatuses([ProductOfferConfig::STATUS_APPROVED])
            ->setIdStore(
                $this->storeFacade->getStoreByName($storeTransfer->getName())->getIdStore(),
            );
        $productOfferCollectionTransfer = $this->productOfferRepository->get($productOfferCriteriaTransfer);

        $indexedProductConcreteTransfers = $this->indexByProductOfferReferences($productOfferCollectionTransfer);
        $filteredItemTransfers = new ArrayObject();
        $messageTransfersIndexedBySku = [];

        foreach ($itemTransfers as $key => $itemTransfer) {
            if ($this->isProductOfferInactive($itemTransfer, $indexedProductConcreteTransfers, $itemProductOfferReferencesToSkipFiltering)) {
                $messageTransfersIndexedBySku = $this->addFilterMessage($itemTransfer->getSku(), $messageTransfersIndexedBySku);

                continue;
            }

            $filteredItemTransfers->offsetSet($key, $itemTransfer);
        }

        return $filteredItemTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $indexedProductConcreteTransfers
     * @param list<string> $itemProductOfferReferencesToSkipFiltering
     *
     * @return bool
     */
    protected function isProductOfferInactive(
        ItemTransfer $itemTransfer,
        array $indexedProductConcreteTransfers,
        array $itemProductOfferReferencesToSkipFiltering = []
    ): bool {
        return $itemTransfer->getProductOfferReference() !== null &&
            !isset($indexedProductConcreteTransfers[$itemTransfer->getProductOfferReference()]) &&
            !in_array($itemTransfer->getProductOfferReference(), $itemProductOfferReferencesToSkipFiltering);
    }

    /**
     * @param string $sku
     * @param array<string, \Generated\Shared\Transfer\MessageTransfer> $messageTransfersIndexedBySku
     *
     * @return array<string, \Generated\Shared\Transfer\MessageTransfer>
     */
    protected function addFilterMessage(string $sku, array $messageTransfersIndexedBySku): array
    {
        if (isset($messageTransfersIndexedBySku[$sku])) {
            return $messageTransfersIndexedBySku;
        }

        $messageTransfersIndexedBySku[$sku] = (new MessageTransfer())
            ->setValue(static::MESSAGE_INFO_OFFER_INACTIVE_PRODUCT_REMOVED)
            ->setParameters([
                static::MESSAGE_PARAM_SKU => $sku,
            ]);

        $this->messengerFacade->addInfoMessage($messageTransfersIndexedBySku[$sku]);

        return $messageTransfersIndexedBySku;
    }

    /**
     * @param \ArrayObject<int,\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     *
     * @return list<string>
     */
    protected function getProductOfferReferencesFromItems(ArrayObject $itemTransfers): array
    {
        $productOfferReferences = [];

        foreach ($itemTransfers as $itemTransfer) {
            if ($itemTransfer->getProductOfferReference()) {
                $productOfferReferences[] = $itemTransfer->getProductOfferReference();
            }
        }

        return $productOfferReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function indexByProductOfferReferences(ProductOfferCollectionTransfer $productOfferCollectionTransfer): array
    {
        $indexedProductOfferTransfers = [];

        foreach ($productOfferCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            $indexedProductOfferTransfers[$productOfferTransfer->getProductOfferReference()] = $productOfferTransfer;
        }

        return $indexedProductOfferTransfers;
    }
}
