<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\SellableItemRequestTransfer;
use Generated\Shared\Transfer\SellableItemsRequestTransfer;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeInterface;
use Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToStoreFacadeInterface;

class ProductOfferReplacementFinder implements ProductOfferReplacementFinderInterface
{
    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToStoreFacadeInterface
     */
    protected ClickAndCollectExampleToStoreFacadeInterface $storeFacade;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeInterface
     */
    protected ClickAndCollectExampleToAvailabilityFacadeInterface $availabilityFacade;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface
     */
    protected ProductOfferReplacementCheckerInterface $productOfferReplacementChecker;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToStoreFacadeInterface $storeFacade
     * @param \Spryker\Zed\ClickAndCollectExample\Dependency\Facade\ClickAndCollectExampleToAvailabilityFacadeInterface $availabilityFacade
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface $productOfferReplacementChecker
     */
    public function __construct(
        ClickAndCollectExampleToStoreFacadeInterface $storeFacade,
        ClickAndCollectExampleToAvailabilityFacadeInterface $availabilityFacade,
        ProductOfferReplacementCheckerInterface $productOfferReplacementChecker
    ) {
        $this->productOfferReplacementChecker = $productOfferReplacementChecker;
        $this->availabilityFacade = $availabilityFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findSuitableProductOffer(ItemTransfer $itemTransfer, array $productOfferServicePointTransfers): ?ProductOfferTransfer
    {
        $sellableItemsRequestTransfer = $this->createSellableItemsRequestTransfer(
            $itemTransfer,
            $productOfferServicePointTransfers,
        );

        $sellableItemsResponseTransfer = $this->availabilityFacade->areProductsSellableForStore($sellableItemsRequestTransfer);
        $sellableItemResponseTransfersIndexedByProductOfferReference = $this->getSellableItemResponseTransfersIndexedByProductOfferReference(
            $sellableItemsResponseTransfer->getSellableItemResponses(),
        );
        $productOfferServicePointTransfers = $this->syncProductOfferStockQuantities(
            $this->cloneProductOfferServicePointTransfers($productOfferServicePointTransfers),
            $sellableItemResponseTransfersIndexedByProductOfferReference,
        );

        $productOfferServicePointReplacementCandidates = [];
        foreach ($productOfferServicePointTransfers as $productOfferServicePointTransfer) {
            if (!$this->productOfferReplacementChecker->isProductOfferServicePointReplaceable($itemTransfer, $productOfferServicePointTransfer)) {
                continue;
            }

            if (!$this->checkProductOfferServicePointAvailability($itemTransfer, $productOfferServicePointTransfer)) {
                continue;
            }

            $productOfferServicePointReplacementCandidates[] = $productOfferServicePointTransfer;
        }

        return $this->findBestPriceProductOffer($productOfferServicePointReplacementCandidates);
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\SellableItemResponseTransfer> $sellableItemResponseTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\SellableItemResponseTransfer>
     */
    protected function getSellableItemResponseTransfersIndexedByProductOfferReference(
        ArrayObject $sellableItemResponseTransfers
    ): array {
        $indexedSellableItemResponseTransfers = [];

        foreach ($sellableItemResponseTransfers as $sellableItemResponseTransfer) {
            $productOfferReference = $sellableItemResponseTransfer
                ->getProductAvailabilityCriteriaOrFail()
                ->getProductOfferReferenceOrFail();

            $indexedSellableItemResponseTransfers[$productOfferReference] = $sellableItemResponseTransfer;
        }

        return $indexedSellableItemResponseTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointTransfer $productOfferServicePointTransfer
     *
     * @return bool
     */
    protected function checkProductOfferServicePointAvailability(
        ItemTransfer $itemTransfer,
        ProductOfferServicePointTransfer $productOfferServicePointTransfer
    ): bool {
        $productOfferStockTransfer = $productOfferServicePointTransfer->getProductOfferStock();
        if (!$productOfferStockTransfer) {
            return false;
        }

        if ($productOfferStockTransfer->getIsNeverOutOfStock()) {
            return true;
        }

        $itemQuantity = $itemTransfer->getQuantityOrFail();
        $productOfferStockQuantity = $productOfferStockTransfer->getQuantityOrFail()->toInt();
        if ($itemQuantity > $productOfferStockQuantity) {
            return false;
        }

        $productOfferStockTransfer->setQuantity($productOfferStockQuantity - $itemQuantity);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfers
     *
     * @return \Generated\Shared\Transfer\SellableItemsRequestTransfer
     */
    protected function createSellableItemsRequestTransfer(
        ItemTransfer $itemTransfer,
        array $productOfferServicePointTransfers
    ): SellableItemsRequestTransfer {
        $sellableItemsRequestTransfer = (new SellableItemsRequestTransfer())->setStore(
            $this->storeFacade->getCurrentStore(),
        );

        foreach ($productOfferServicePointTransfers as $productOfferServicePointTransfer) {
            $productAvailabilityCriteriaTransfer = (new ProductAvailabilityCriteriaTransfer())
                ->fromArray($itemTransfer->toArray(), true)
                ->setProductOfferReference($productOfferServicePointTransfer->getProductOfferOrFail()->getProductOfferReferenceOrFail());

            $sellableItemRequestTransfer = (new SellableItemRequestTransfer())
                ->setProductAvailabilityCriteria($productAvailabilityCriteriaTransfer)
                ->setQuantity($itemTransfer->getQuantityOrFail())
                ->setSku($itemTransfer->getSkuOrFail());

            $sellableItemsRequestTransfer->addSellableItemRequest($sellableItemRequestTransfer);
        }

        return $sellableItemsRequestTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfers
     * @param array<string, \Generated\Shared\Transfer\SellableItemResponseTransfer> $sellableItemResponseTransfersIndexedByProductOfferReference
     *
     * @return list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    protected function syncProductOfferStockQuantities(
        array $productOfferServicePointTransfers,
        array $sellableItemResponseTransfersIndexedByProductOfferReference
    ): array {
        foreach ($productOfferServicePointTransfers as $productOfferServicePointTransfer) {
            $productOfferReference = $productOfferServicePointTransfer->getProductOfferOrFail()->getProductOfferReferenceOrFail();
            $sellableItemResponseTransfer = $sellableItemResponseTransfersIndexedByProductOfferReference[$productOfferReference] ?? null;

            if (!$sellableItemResponseTransfer || !$sellableItemResponseTransfer->getIsSellable()) {
                $productOfferServicePointTransfer->setProductOfferStock(null);

                continue;
            }

            $productOfferServicePointTransfer->getProductOfferStockOrFail()->setQuantity(
                $sellableItemResponseTransfer->getAvailableQuantityOrFail(),
            );
        }

        return $productOfferServicePointTransfers;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointReplacementCandidates
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    protected function findBestPriceProductOffer(array $productOfferServicePointReplacementCandidates): ?ProductOfferTransfer
    {
        if (count($productOfferServicePointReplacementCandidates) === 0) {
            return null;
        }

        $bestPriceProductOfferServicePointTransfer = null;
        foreach ($productOfferServicePointReplacementCandidates as $productOfferServicePointTransfer) {
            if ($bestPriceProductOfferServicePointTransfer === null) {
                $bestPriceProductOfferServicePointTransfer = $productOfferServicePointTransfer;
            }

            if ($productOfferServicePointTransfer->getProductOfferPrice() < $bestPriceProductOfferServicePointTransfer->getProductOfferPrice()) {
                $bestPriceProductOfferServicePointTransfer = $productOfferServicePointTransfer;
            }
        }

        return $bestPriceProductOfferServicePointTransfer->getProductOfferOrFail();
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    protected function cloneProductOfferServicePointTransfers(array $productOfferServicePointTransfers): array
    {
        $clonedProductOfferServicePointTransfers = [];
        foreach ($productOfferServicePointTransfers as $key => $productOfferServicePointTransfer) {
            $clonedProductOfferServicePointTransfers[$key] = (new ProductOfferServicePointTransfer())
                ->fromArray($productOfferServicePointTransfer->toArray());
        }

        return $clonedProductOfferServicePointTransfers;
    }
}
