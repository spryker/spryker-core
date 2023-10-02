<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Expander;

use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;
use Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface;

class ProductOfferServicePointExpander implements ProductOfferServicePointExpanderInterface
{
    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface
     */
    protected ClickAndCollectExampleRepositoryInterface $clickAndCollectExampleRepository;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface $clickAndCollectExampleRepository
     */
    public function __construct(
        ClickAndCollectExampleRepositoryInterface $clickAndCollectExampleRepository
    ) {
        $this->clickAndCollectExampleRepository = $clickAndCollectExampleRepository;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfersIndexedByIdProductOffer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function expandProductOfferServicePointsWithProductOfferStocks(array $productOfferServicePointTransfersIndexedByIdProductOffer): array
    {
        $productOfferStockTransfers = $this->clickAndCollectExampleRepository
            ->getProductOfferStocksByCriteria(
                (new ProductOfferServicePointCriteriaTransfer())->setProductOfferIds(array_keys($productOfferServicePointTransfersIndexedByIdProductOffer)),
            );

        foreach ($productOfferStockTransfers as $productOfferStockTransfer) {
            $idProductOffer = $productOfferStockTransfer->getIdProductOfferOrFail();
            if (!isset($productOfferServicePointTransfersIndexedByIdProductOffer[$idProductOffer])) {
                continue;
            }

            $productOfferServicePointTransfer = $productOfferServicePointTransfersIndexedByIdProductOffer[$idProductOffer];
            if (!$productOfferServicePointTransfer->getProductOfferStock()) {
                $productOfferServicePointTransfer->setProductOfferStock($productOfferStockTransfer);

                continue;
            }

            if ($productOfferServicePointTransfer->getProductOfferStockOrFail()->getIsNeverOutOfStock()) {
                continue;
            }

            $currentAvailability = $productOfferServicePointTransfer->getProductOfferStockOrFail()->getQuantityOrFail()->toInt();
            $productOfferServicePointTransfer->getProductOfferStockOrFail()->setQuantity($currentAvailability + $productOfferStockTransfer->getQuantityOrFail()->toInt());
        }

        return $productOfferServicePointTransfersIndexedByIdProductOffer;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfersIndexedByIdProductOffer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function expandProductOfferServicePointsWithProductOfferPrices(
        array $productOfferServicePointTransfersIndexedByIdProductOffer,
        ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
    ): array {
        $productOfferServicePointCriteriaTransfer->setProductOfferIds(array_keys($productOfferServicePointTransfersIndexedByIdProductOffer));
        $productOfferPriceTransfers = $this
            ->clickAndCollectExampleRepository
            ->getProductOfferPricesByCriteria($productOfferServicePointCriteriaTransfer);

        foreach ($productOfferPriceTransfers as $productOfferPriceTransfer) {
            $idProductOffer = $productOfferPriceTransfer->getIdProductOfferOrFail();
            if (!isset($productOfferServicePointTransfersIndexedByIdProductOffer[$idProductOffer])) {
                continue;
            }

            $productOfferServicePointTransfer = $productOfferServicePointTransfersIndexedByIdProductOffer[$idProductOffer];
            $productOfferServicePointTransfer->setProductOfferPrice($productOfferPriceTransfer->getPriceOrFail());
        }

        return $productOfferServicePointTransfersIndexedByIdProductOffer;
    }
}
