<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Reader;

use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface;
use Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface;

class ProductOfferServiceReader implements ProductOfferServiceReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface
     */
    protected ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface
     */
    protected ProductOfferServiceExpanderInterface $productOfferServiceExpander;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Expander\ProductOfferServiceExpanderInterface $productOfferServiceExpander
     */
    public function __construct(
        ProductOfferServicePointRepositoryInterface $productOfferServicePointRepository,
        ProductOfferServiceExpanderInterface $productOfferServiceExpander
    ) {
        $this->productOfferServicePointRepository = $productOfferServicePointRepository;
        $this->productOfferServiceExpander = $productOfferServiceExpander;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollection(
        ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer {
        $productOfferServiceCollectionTransfer = $this->productOfferServicePointRepository->getProductOfferServiceCollection(
            $productOfferServiceCriteriaTransfer,
        );
        $productOfferServiceConditionsTransfer = $productOfferServiceCriteriaTransfer->getProductOfferServiceConditions();

        if ($productOfferServiceConditionsTransfer && $productOfferServiceConditionsTransfer->getWithServicePointRelations()) {
            $productOfferServiceCollectionTransfer = $this->productOfferServiceExpander->expandProductOfferServiceCollectionWithServicePoints(
                $productOfferServiceCollectionTransfer,
            );
        }

        return $productOfferServiceCollectionTransfer;
    }
}
