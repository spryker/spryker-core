<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePoint\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferServiceCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferServicePoint\ProductOfferServicePointConfig getConfig()
 * @method \Spryker\Zed\ProductOfferServicePoint\Business\ProductOfferServicePointFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferServicePoint\Communication\ProductOfferServicePointCommunicationFactory getFactory()
 */
class ServiceProductOfferPostUpdatePlugin extends AbstractPlugin implements ProductOfferPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferServiceCollectionRequestTransfer.productOffers` to be set.
     * - Requires `ProductOfferServiceCollectionRequestTransfer.isTransactional` to be set.
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Requires `ProductOfferTransfer.services.uuid` to be set.
     * - Validates product offer reference existence using `ProductOfferTransfer.productOfferReference`.
     * - Validates service existence using `ProductOfferTransfer.services.uuid`.
     * - Validates service uniqueness in scope of request collection.
     * - Validates product offer has single service point.
     * - Uses `ProductOfferServiceCollectionRequestTransfer.isTransactional` for transactional operation.
     * - Throws {@link \Spryker\Zed\ProductOfferServicePoint\Business\Exception\ProductOfferValidationException} when `ProductOfferServiceCollectionRequestTransfer.throwExceptionOnValidation` enabled and validation fails.
     * - Stores updated product offer service entities to persistence.
     * - Returns `ProductOfferServiceCollectionResponseTransfer` with product offers and errors if any occurred.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function execute(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferServiceCollectionRequestTransfer())
            ->addProductOffer($productOfferTransfer)
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(true);
        $productOfferServiceCollectionResponseTransfer = $this->getFacade()->saveProductOfferServices($productOfferServiceCollectionRequestTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferServiceCollectionResponseTransfer->getProductOffers();

        return $productOfferTransfers->getIterator()->current();
    }
}
