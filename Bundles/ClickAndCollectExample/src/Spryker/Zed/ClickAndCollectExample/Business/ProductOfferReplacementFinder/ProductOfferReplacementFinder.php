<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementFinder;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface;

class ProductOfferReplacementFinder implements ProductOfferReplacementFinderInterface
{
    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface
     */
    protected ProductOfferReplacementCheckerInterface $productOfferReplacementChecker;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Business\ProductOfferReplacementChecker\ProductOfferReplacementCheckerInterface $productOfferReplacementChecker
     */
    public function __construct(
        ProductOfferReplacementCheckerInterface $productOfferReplacementChecker
    ) {
        $this->productOfferReplacementChecker = $productOfferReplacementChecker;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfers
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer|null
     */
    public function findSuitableProductOffer(ItemTransfer $itemTransfer, array $productOfferServicePointTransfers): ?ProductOfferTransfer
    {
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
}
