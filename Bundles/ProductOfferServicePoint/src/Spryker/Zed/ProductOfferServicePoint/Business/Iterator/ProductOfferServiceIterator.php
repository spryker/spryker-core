<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Iterator;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface;
use Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface;
use Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig;

class ProductOfferServiceIterator implements ProductOfferServiceIteratorInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig
     */
    protected ProductOfferServicePointConfig $productOfferServicePointConfig;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface
     */
    protected ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface
     */
    protected ProductOfferServiceExpanderInterface $productOfferServiceExpander;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig $productOfferServicePointConfig
     * @param \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface $productOfferServiceExpander
     */
    public function __construct(
        ProductOfferServicePointConfig $productOfferServicePointConfig,
        ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository,
        ProductOfferServiceExpanderInterface $productOfferServiceExpander
    ) {
        $this->productOfferServicePointConfig = $productOfferServicePointConfig;
        $this->productOfferServicePointRepository = $productOfferServicePointRepository;
        $this->productOfferServiceExpander = $productOfferServiceExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return iterable<list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>>
     */
    public function iterateProductOfferServices(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): iterable {
        $productOfferIdsChunks = array_chunk(
            $iterableProductOfferServicesCriteriaTransfer->getIterableProductOfferServicesConditionsOrFail()->getProductOfferIds(),
            $this->productOfferServicePointConfig->getProductOfferServicesProcessBatchSize(),
        );

        $productOfferConditions = (new ProductOfferServiceConditionsTransfer())
            ->setGroupByIdProductOffer(true);

        foreach ($productOfferIdsChunks as $productOfferIds) {
            $productOfferConditions->setProductOfferIds($productOfferIds);
            $productOfferServiceCriteriaTransfer = (new ProductOfferServiceCriteriaTransfer())
                ->setProductOfferServiceConditions($productOfferConditions);

            $productOfferServiceCollectionTransfer = $this->productOfferServicePointRepository
                ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);

            $productOfferServiceCollectionTransfer = $this->expandProductOfferServiceCollection(
                $productOfferServiceCollectionTransfer,
                $iterableProductOfferServicesCriteriaTransfer,
            );

            /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferServicesTransfer> $productOfferServicesTransfers */
            $productOfferServicesTransfers = $productOfferServiceCollectionTransfer->getProductOfferServices();

            yield $productOfferServicesTransfers->getArrayCopy();
        }
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    protected function expandProductOfferServiceCollection(
        ProductOfferServiceCollectionTransfer $productOfferServiceCollectionTransfer,
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer {
        $this->productOfferServiceExpander->expandProductOfferServiceCollectionWithProductOffersByIterableProductOfferServicesCriteria(
            $productOfferServiceCollectionTransfer,
            $iterableProductOfferServicesCriteriaTransfer,
        );

        return $this->productOfferServiceExpander->expandProductOfferServiceCollectionWithServicesByIterableProductOfferServicesCriteria(
            $productOfferServiceCollectionTransfer,
            $iterableProductOfferServicesCriteriaTransfer,
        );
    }
}
