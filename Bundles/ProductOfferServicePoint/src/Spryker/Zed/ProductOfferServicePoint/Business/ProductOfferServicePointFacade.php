<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business;

use Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer;
use Spryker\Zed\Kernel\Business\AbstractFacade;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointBusinessFactory getFactory()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointRepositoryInterface getRepository()
 * @method \Spryker\Zed\ProductOfferServicePoint\Persistence\ProductOfferServicePointEntityManagerInterface getEntityManager()
 */
class ProductOfferServicePointFacade extends AbstractFacade implements ProductOfferServicePointFacadeInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferCollectionTransfer $productOfferCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferCollectionTransfer
     */
    public function expandProductOfferCollectionWithServices(ProductOfferCollectionTransfer $productOfferCollectionTransfer): ProductOfferCollectionTransfer
    {
        return $this->getFactory()
            ->createProductOfferExpander()
            ->expandProductOfferCollectionWithServices($productOfferCollectionTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer
     */
    public function saveProductOfferServices(
        ProductOfferServiceCollectionRequestTransfer $productOfferServiceCollectionRequestTransfer
    ): ProductOfferServiceCollectionResponseTransfer {
        return $this->getFactory()
            ->createProductOfferServiceSaver()
            ->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferServiceCollectionTransfer
     */
    public function getProductOfferServiceCollection(
        ProductOfferServiceCriteriaTransfer $productOfferServiceCriteriaTransfer
    ): ProductOfferServiceCollectionTransfer {
        return $this->getFactory()
            ->createProductOfferServiceReader()
            ->getProductOfferServiceCollection($productOfferServiceCriteriaTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
     *
     * @return iterable<list<\Generated\Shared\Transfer\ProductOfferServicesTransfer>>
     */
    public function iterateProductOfferServices(
        IterableProductOfferServicesCriteriaTransfer $iterableProductOfferServicesCriteriaTransfer
    ): iterable {
        return $this->getFactory()
            ->createProductOfferServiceIterator()
            ->iterateProductOfferServices($iterableProductOfferServicesCriteriaTransfer);
    }
}
