<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business\Reader;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferConditionsTransfer;
use Generated\Shared\Transfer\ProductOfferCriteriaTransfer;
use Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface;
use Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToProductOfferFacadeInterface;

class ProductOfferReader implements ProductOfferReaderInterface
{
    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToProductOfferFacadeInterface
     */
    protected ProductOfferServicePointToProductOfferFacadeInterface $productOfferFacade;

    /**
     * @var \Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface
     */
    protected ProductOfferServiceMapperInterface $productOfferServiceMapper;

    /**
     * @param \Spryker\Zed\ProductOfferServicePoint\Dependency\Facade\ProductOfferServicePointToProductOfferFacadeInterface $productOfferFacade
     * @param \Spryker\Zed\ProductOfferServicePoint\Business\Mapper\ProductOfferServiceMapperInterface $productOfferServiceMapper
     */
    public function __construct(
        ProductOfferServicePointToProductOfferFacadeInterface $productOfferFacade,
        ProductOfferServiceMapperInterface $productOfferServiceMapper
    ) {
        $this->productOfferFacade = $productOfferFacade;
        $this->productOfferServiceMapper = $productOfferServiceMapper;
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
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function getProductOfferCollectionByIterableProductOfferServicesCriteria(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): ProductOfferCollectionTransfer {
        $productOfferCriteriaTransfer = $this->productOfferServiceMapper->mapIterableProductOfferServicesCriteriaTransferToProductOfferCriteriaTransfer(
            $iterableProductOfferServicesCriteriaTransfer,
            new ProductOfferCriteriaTransfer(),
        );

        return $this->productOfferFacade->get($productOfferCriteriaTransfer);
    }
}
