<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductOfferServicePointAvailabilityStorage\Reader;

use ArrayObject;
use Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractorInterface;
use Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ProductOfferStorageFilterInterface;

class ProductOfferStorageReader implements ProductOfferStorageReaderInterface
{
    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface
     */
    protected ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface $productOfferStorageClient;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractorInterface
     */
    protected ProductOfferServicePointAvailabilityRequestItemExtractorInterface $productOfferServicePointAvailabilityRequestItemExtractor;

    /**
     * @var \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ProductOfferStorageFilterInterface
     */
    protected ProductOfferStorageFilterInterface $productOfferStorageFilter;

    /**
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Dependency\Client\ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface $productOfferStorageClient
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Extractor\ProductOfferServicePointAvailabilityRequestItemExtractorInterface $productOfferServicePointAvailabilityRequestItemExtractor
     * @param \Spryker\Client\ProductOfferServicePointAvailabilityStorage\Filter\ProductOfferStorageFilterInterface $productOfferStorageFilter
     */
    public function __construct(
        ProductOfferServicePointAvailabilityStorageToProductOfferStorageClientInterface $productOfferStorageClient,
        ProductOfferServicePointAvailabilityRequestItemExtractorInterface $productOfferServicePointAvailabilityRequestItemExtractor,
        ProductOfferStorageFilterInterface $productOfferStorageFilter
    ) {
        $this->productOfferStorageClient = $productOfferStorageClient;
        $this->productOfferServicePointAvailabilityRequestItemExtractor = $productOfferServicePointAvailabilityRequestItemExtractor;
        $this->productOfferStorageFilter = $productOfferStorageFilter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function getProductOfferStorageTransfersByProductOfferServicePointAvailabilityConditions(
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): ArrayObject {
        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityRequestItemTransfer> $productOfferServicePointAvailabilityRequestItemTransfers */
        $productOfferServicePointAvailabilityRequestItemTransfers = $productOfferServicePointAvailabilityConditionsTransfer->getProductOfferServicePointAvailabilityRequestItems();

        $productConcreteSkus = $this->productOfferServicePointAvailabilityRequestItemExtractor->extractProductConcreteSkusFromProductOfferServicePointAvailabilityRequestItems(
            $productOfferServicePointAvailabilityRequestItemTransfers,
        );

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers */
        $productOfferStorageTransfers = $this->productOfferStorageClient->getProductOfferStoragesBySkus(
            (new ProductOfferStorageCriteriaTransfer())->setProductConcreteSkus(array_unique($productConcreteSkus)),
        )->getProductOffers();

        return $this->filterProductOfferStorageByProductOfferServicePointAvailabilityConditions(
            $productOfferStorageTransfers,
            $productOfferServicePointAvailabilityConditionsTransfer,
        );
    }

    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOfferStorageTransfers
     * @param \Generated\Shared\Transfer\ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    protected function filterProductOfferStorageByProductOfferServicePointAvailabilityConditions(
        ArrayObject $productOfferStorageTransfers,
        ProductOfferServicePointAvailabilityConditionsTransfer $productOfferServicePointAvailabilityConditionsTransfer
    ): ArrayObject {
        $productOfferStorageTransfers = $this->productOfferStorageFilter->filterProductOfferStorageServicesByServicePointUuids(
            $productOfferStorageTransfers,
            $productOfferServicePointAvailabilityConditionsTransfer->getServicePointUuids(),
        );

        return $this->productOfferStorageFilter->filterProductOfferStorageServicesByServiceTypeUuid(
            $productOfferStorageTransfers,
            $productOfferServicePointAvailabilityConditionsTransfer->getServiceTypeUuidOrFail(),
        );
    }
}
