<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Business;

use Generated\Shared\Transfer\ProductOfferCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferServiceCollectionResponseTransfer;
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
}
