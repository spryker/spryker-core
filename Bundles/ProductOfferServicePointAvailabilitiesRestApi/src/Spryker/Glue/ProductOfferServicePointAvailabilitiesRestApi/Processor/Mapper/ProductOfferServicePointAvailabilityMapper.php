<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductOfferServicePointAvailabilitiesRestApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer;
use Generated\Shared\Transfer\RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer;

class ProductOfferServicePointAvailabilityMapper implements ProductOfferServicePointAvailabilityMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer $restProductOfferServicePointAvailabilitiesRequestAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityCriteriaTransfer
     */
    public function mapRestProductOfferServicePointAvailabilitiesRequestAttributesTransferToProductOfferServicePointAvailabilityCriteriaTransfer(
        RestProductOfferServicePointAvailabilitiesRequestAttributesTransfer $restProductOfferServicePointAvailabilitiesRequestAttributesTransfer,
        ProductOfferServicePointAvailabilityCriteriaTransfer $productOfferServicePointAvailabilityCriteriaTransfer
    ): ProductOfferServicePointAvailabilityCriteriaTransfer {
        $productOfferServicePointAvailabilityConditions = (new ProductOfferServicePointAvailabilityConditionsTransfer())
            ->fromArray($restProductOfferServicePointAvailabilitiesRequestAttributesTransfer->toArray(), true);

        return $productOfferServicePointAvailabilityCriteriaTransfer
            ->setProductOfferServicePointAvailabilityConditions($productOfferServicePointAvailabilityConditions);
    }

    /**
     * @param array<string, list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer>> $productOfferServicePointAvailabilities
     * @param \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer $restProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer
     */
    public function mapProductOfferServicePointAvailabilitiesToRestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer(
        array $productOfferServicePointAvailabilities,
        RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer $restProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer
    ): RestProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer {
        foreach ($productOfferServicePointAvailabilities as $servicePointUuid => $productOfferServicePointAvailabilityResponseItemTransfers) {
            $restProductOfferServicePointAvailabilitiesResponseAttributesTransfer = $this->mapProductOfferServicePointAvailabilityResponseItemTransfersToRestProductOfferServicePointAvailabilitiesResponseAttributesTransfer(
                $productOfferServicePointAvailabilityResponseItemTransfers,
                new RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer(),
            )
                ->setServicePointUuid($servicePointUuid);

            $restProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer->addProductOfferServicePointAvailability(
                $restProductOfferServicePointAvailabilitiesResponseAttributesTransfer,
            );
        }

        return $restProductOfferServicePointAvailabilitiesResponseAttributesCollectionTransfer;
    }

    /**
     * @param list<\Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer> $productOfferServicePointAvailabilityResponseItemTransfers
     * @param \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer $restProductOfferServicePointAvailabilitiesResponseAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer
     */
    protected function mapProductOfferServicePointAvailabilityResponseItemTransfersToRestProductOfferServicePointAvailabilitiesResponseAttributesTransfer(
        array $productOfferServicePointAvailabilityResponseItemTransfers,
        RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer $restProductOfferServicePointAvailabilitiesResponseAttributesTransfer
    ): RestProductOfferServicePointAvailabilitiesResponseAttributesTransfer {
        foreach ($productOfferServicePointAvailabilityResponseItemTransfers as $productOfferServicePointAvailabilityResponseItemTransfer) {
            $restProductOfferServicePointAvailabilityResponseItemsAttributesTransfer = $this->mapProductOfferServicePointAvailabilityResponseItemTransferToRestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer(
                $productOfferServicePointAvailabilityResponseItemTransfer,
                new RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer(),
            );

            $restProductOfferServicePointAvailabilitiesResponseAttributesTransfer->addProductOfferServicePointAvailabilityResponseItem(
                $restProductOfferServicePointAvailabilityResponseItemsAttributesTransfer,
            );
        }

        return $restProductOfferServicePointAvailabilitiesResponseAttributesTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer
     * @param \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer $restProductOfferServicePointAvailabilityResponseItemsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer
     */
    protected function mapProductOfferServicePointAvailabilityResponseItemTransferToRestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer(
        ProductOfferServicePointAvailabilityResponseItemTransfer $productOfferServicePointAvailabilityResponseItemTransfer,
        RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer $restProductOfferServicePointAvailabilityResponseItemsAttributesTransfer
    ): RestProductOfferServicePointAvailabilityResponseItemsAttributesTransfer {
        return $restProductOfferServicePointAvailabilityResponseItemsAttributesTransfer->fromArray(
            $productOfferServicePointAvailabilityResponseItemTransfer->toArray(),
            true,
        );
    }
}
