<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointStorage\Business\Reader;

use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface;
use Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageRepositoryInterface;

class ProductOfferServiceStorageReader implements ProductOfferServiceStorageReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageRepositoryInterface
     */
    protected ProductOfferServicePointStorageRepositoryInterface $productOfferServicePointStorageRepository;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface
     */
    protected ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReaderInterface
     */
    protected ProductOfferReaderInterface $productOfferReader;

    /**
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Persistence\ProductOfferServicePointStorageRepositoryInterface $productOfferServicePointStorageRepository
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Dependency\Facade\ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade
     * @param \Spryker\Zed\ProductOfferServicePointStorage\Business\Reader\ProductOfferReaderInterface $productOfferReader
     */
    public function __construct(
        ProductOfferServicePointStorageRepositoryInterface $productOfferServicePointStorageRepository,
        ProductOfferServicePointStorageToProductOfferServicePointFacadeInterface $productOfferServicePointFacade,
        ProductOfferReaderInterface $productOfferReader
    ) {
        $this->productOfferServicePointStorageRepository = $productOfferServicePointStorageRepository;
        $this->productOfferServicePointFacade = $productOfferServicePointFacade;
        $this->productOfferReader = $productOfferReader;
    }

    /**
     * @param \Generated\Shared\Transfer\FilterTransfer $filterTransfer
     * @param list<int> $productOfferServiceIds
     *
     * @return list<\Generated\Shared\Transfer\SynchronizationDataTransfer>
     */
    public function getProductOfferServiceStorageSynchronizationDataTransfers(FilterTransfer $filterTransfer, array $productOfferServiceIds = []): array
    {
        if (!$productOfferServiceIds) {
            return $this->productOfferServicePointStorageRepository
                ->getProductOfferServiceStorageSynchronizationDataTransfers($filterTransfer);
        }

        $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())->setProductOfferServiceConditions(
            (new ProductOfferServiceConditionsTransfer())
                ->setProductOfferServiceIds($productOfferServiceIds)
                ->setGroupByIdProductOffer(true),
        );

        $productOfferIds = $this->extractProductOfferIdsFromProductOfferServiceCollectionTransfer(
            $this->productOfferServicePointFacade->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer),
        );

        $productOfferReferences = $this->productOfferReader->getProductOfferReferencesByProductOfferIds($productOfferIds);
        if (!count($productOfferReferences)) {
            return [];
        }

        return $this->productOfferServicePointStorageRepository
            ->getProductOfferServiceStorageSynchronizationDataTransfers($filterTransfer, $productOfferReferences);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     *
     * @return list<int>
     */
    protected function extractProductOfferIdsFromProductOfferServiceCollectionTransfer(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
    ): array {
        $productOfferIds = [];
        foreach ($productOfferServiceCollectionTransfer->getProductOfferServices() as $productOfferServicesTransfer) {
            $productOfferIds[] = $productOfferServicesTransfer->getProductOfferOrFail()->getIdProductOfferOrFail();
        }

        return $productOfferIds;
    }
}
