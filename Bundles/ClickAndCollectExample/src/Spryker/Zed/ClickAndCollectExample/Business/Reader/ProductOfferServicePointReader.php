<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ClickAndCollectExample\Business\Reader;

use Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer;
use Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpanderInterface;
use Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface;

class ProductOfferServicePointReader implements ProductOfferServicePointReaderInterface
{
    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface
     */
    protected ClickAndCollectExampleRepositoryInterface $clickAndCollectExampleRepository;

    /**
     * @var \Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpanderInterface
     */
    protected ProductOfferServicePointExpanderInterface $productOfferServicePointExpander;

    /**
     * @param \Spryker\Zed\ClickAndCollectExample\Persistence\ClickAndCollectExampleRepositoryInterface $clickAndCollectExampleRepository
     * @param \Spryker\Zed\ClickAndCollectExample\Business\Expander\ProductOfferServicePointExpanderInterface $servicePointExpander
     */
    public function __construct(
        ClickAndCollectExampleRepositoryInterface $clickAndCollectExampleRepository,
        ProductOfferServicePointExpanderInterface $servicePointExpander
    ) {
        $this->clickAndCollectExampleRepository = $clickAndCollectExampleRepository;
        $this->productOfferServicePointExpander = $servicePointExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getPickupProductOfferServicePoints(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array
    {
        $productOfferServicePointTransfers = $this->clickAndCollectExampleRepository->getPickupProductOfferServicePointsByCriteria($productOfferServicePointCriteriaTransfer);
        $productOfferServicePointTransfersIndexedByIdProductOffer = $this
            ->getProductOfferServicePointTransfersIndexedByIdProductOffer($productOfferServicePointTransfers);

        return $this->expandProductOfferServicePointTransfersWithRelations($productOfferServicePointTransfersIndexedByIdProductOffer, $productOfferServicePointCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    public function getDeliveryProductOfferServicePoints(ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer): array
    {
        $productOfferServicePointTransfers = $this->clickAndCollectExampleRepository->getDeliveryProductOfferServicePointsByCriteria($productOfferServicePointCriteriaTransfer);
        $productOfferServicePointTransfersIndexedByIdProductOffer = $this
            ->getProductOfferServicePointTransfersIndexedByIdProductOffer($productOfferServicePointTransfers);

        return $this->expandProductOfferServicePointTransfersWithRelations($productOfferServicePointTransfersIndexedByIdProductOffer, $productOfferServicePointCriteriaTransfer);
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfers
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    protected function getProductOfferServicePointTransfersIndexedByIdProductOffer(array $productOfferServicePointTransfers): array
    {
        $productOfferServicePointTransfersIndexedByIdProductOffer = [];
        foreach ($productOfferServicePointTransfers as $productOfferServicePointTransfer) {
            $indexKey = $productOfferServicePointTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
            $productOfferServicePointTransfersIndexedByIdProductOffer[$indexKey] = $productOfferServicePointTransfer;
        }

        return $productOfferServicePointTransfersIndexedByIdProductOffer;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer> $productOfferServicePointTransfersIndexedByIdProductOffer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
     *
     * @return array<int, \Generated\Shared\Transfer\ProductOfferServicePointTransfer>
     */
    protected function expandProductOfferServicePointTransfersWithRelations(
        array $productOfferServicePointTransfersIndexedByIdProductOffer,
        ProductOfferServicePointCriteriaTransfer $productOfferServicePointCriteriaTransfer
    ): array {
        $productOfferServicePointTransfersIndexedByIdProductOffer = $this
            ->productOfferServicePointExpander
            ->expandProductOfferServicePointsWithProductOfferStocks($productOfferServicePointTransfersIndexedByIdProductOffer);

        return $this
            ->productOfferServicePointExpander
            ->expandProductOfferServicePointsWithProductOfferPrices(
                $productOfferServicePointTransfersIndexedByIdProductOffer,
                $productOfferServicePointCriteriaTransfer,
            );
    }
}
