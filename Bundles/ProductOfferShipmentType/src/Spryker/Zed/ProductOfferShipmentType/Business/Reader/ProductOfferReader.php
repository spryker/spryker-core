<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Business\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer;
use Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionExpanderInterface;
use Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferProductOfferShipmentTypeCollectionFilterInterface;
use Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToProductOfferFacadeInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferProductOfferShipmentTypeCollectionFilterInterface
     */
    protected ProductOfferProductOfferShipmentTypeCollectionFilterInterface $productOfferProductOfferShipmentTypeCollectionFilter;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionExpanderInterface
     */
    protected ProductOfferProductOfferShipmentTypeCollectionExpanderInterface $productOfferProductOfferShipmentTypeCollectionExpander;

    /**
     * @var \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToProductOfferFacadeInterface
     */
    protected ProductOfferShipmentTypeToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Filter\ProductOfferProductOfferShipmentTypeCollectionFilterInterface $productOfferProductOfferShipmentTypeCollectionFilter
     * @param \Spryker\Zed\ProductOfferShipmentType\Business\Expander\ProductOfferProductOfferShipmentTypeCollectionExpanderInterface $productOfferProductOfferShipmentTypeCollectionExpander
     * @param \Spryker\Zed\ProductOfferShipmentType\Dependency\Facade\ProductOfferShipmentTypeToProductOfferFacadeInterface $productOfferFacade
     */
    public function __construct(
        ProductOfferProductOfferShipmentTypeCollectionFilterInterface $productOfferProductOfferShipmentTypeCollectionFilter,
        ProductOfferProductOfferShipmentTypeCollectionExpanderInterface $productOfferProductOfferShipmentTypeCollectionExpander,
        ProductOfferShipmentTypeToProductOfferFacadeInterface $productOfferFacade
    ) {
        $this->productOfferProductOfferShipmentTypeCollectionFilter = $productOfferProductOfferShipmentTypeCollectionFilter;
        $this->productOfferProductOfferShipmentTypeCollectionExpander = $productOfferProductOfferShipmentTypeCollectionExpander;
        $this->productOfferFacade = $productOfferFacade;
    }

    /**
     * @param list<string> $productOfferReferences
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByProductOfferReferences(array $productOfferReferences): ProductOfferCollectionTransfer
    {
        $productOfferConditionsTransfer = (new ProductOfferConditionsTransfer())
            ->setProductOfferReferences($productOfferReferences);
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferConditions($productOfferConditionsTransfer);

        return $this->productOfferFacade->getProductOfferCollection($productOfferCriteriaTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionTransfer
     */
    public function getProductOffersForProductOfferShipmentTypeCollection(
        ProductOfferShipmentTypeCollectionTransfer $productOfferShipmentTypeCollectionTransfer,
        ProductOfferShipmentTypeIteratorCriteriaTransfer $productOfferShipmentTypeIteratorCriteriaTransfer
    ): ProductOfferShipmentTypeCollectionTransfer {
        $productOfferCollectionTransfer = $this->getProductOfferCollectionTransfer(
            $productOfferShipmentTypeIteratorCriteriaTransfer->getProductOfferShipmentTypeIteratorConditionsOrFail(),
            $this->extractProductOfferIdsFromProductOfferShipmentTypeTransfers(
                $productOfferShipmentTypeCollectionTransfer->getProductOfferShipmentTypes(),
            ),
        );
        $productOfferShipmentTypeCollectionTransfer = $this->productOfferProductOfferShipmentTypeCollectionFilter
            ->filterProductOfferShipmentTypeCollectionTransfersByProductOfferCollectionTransfer(
                $productOfferShipmentTypeCollectionTransfer,
                $productOfferCollectionTransfer,
            );

        return $this->productOfferProductOfferShipmentTypeCollectionExpander
            ->expandProductOfferShipmentTypeCollectionWithProductOffers(
                $productOfferShipmentTypeCollectionTransfer,
                $productOfferCollectionTransfer,
            );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferShipmentTypeTransfer> $productOfferShipmentTypeTransfers
     *
     * @return list<int>
     */
    protected function extractProductOfferIdsFromProductOfferShipmentTypeTransfers(ArrayObject $productOfferShipmentTypeTransfers): array
    {
        $productOfferIds = [];
        foreach ($productOfferShipmentTypeTransfers as $productOfferShipmentTypeTransfer) {
            $productOfferIds[] = $productOfferShipmentTypeTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
        }

        return $productOfferIds;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferShipmentTypeIteratorConditionsTransfer $productOfferShipmentTypeIteratorConditionsTransfer
     * @param list<int> $productOfferIds
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    protected function getProductOfferCollectionTransfer(
        ProductOfferShipmentTypeIteratorConditionsTransfer $productOfferShipmentTypeIteratorConditionsTransfer,
        array $productOfferIds
    ): ProductOfferCollectionTransfer {
        $productOfferCriteriaTransfer = (new ProductOfferCriteriaTransfer())
            ->setProductOfferConditions((new ProductOfferConditionsTransfer())->setProductOfferIds($productOfferIds))
            ->setIsActive($productOfferShipmentTypeIteratorConditionsTransfer->getIsActiveProductOffer())
            ->setIsActiveConcreteProduct($productOfferShipmentTypeIteratorConditionsTransfer->getIsActiveProductOfferConcreteProduct())
            ->setApprovalStatuses($productOfferShipmentTypeIteratorConditionsTransfer->getProductOfferApprovalStatuses());

        return $this->productOfferFacade->get($productOfferCriteriaTransfer);
    }
}
