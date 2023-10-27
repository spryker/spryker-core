<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentType\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferShipmentTypeCollectionRequestTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferPostUpdatePluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShipmentType\Business\ProductOfferShipmentTypeFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferShipmentType\ProductOfferShipmentTypeConfig getConfig()
 */
class ShipmentTypeProductOfferPostUpdatePlugin extends AbstractPlugin implements ProductOfferPostUpdatePluginInterface
{
    /**
     * {@inheritDoc}
     * - Requires `ProductOfferTransfer.productOfferReference` to be set.
     * - Requires `ProductOfferTransfer.shipmentTypes.uuid` to be set.
     * - Validates product offer reference existence using `ProductOfferTransfer.productOfferReference`.
     * - Validates product offer reference uniqueness in scope of request collection.
     * - Validates shipment type existence using `ProductOfferTransfer.shipmentTypes.uuid`.
     * - Validates shipment type uniqueness for each `ProductOfferShipmentTypeCollectionRequestTransfer.productOffers`.
     * - Throws {@link \Spryker\Zed\ProductOfferShipmentType\Business\Exception\ProductOfferValidationException} when validation fails.
     * - Stores valid product offer shipment type entities to persistence.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function execute(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        $productOfferServiceCollectionRequestTransfer = (new ProductOfferShipmentTypeCollectionRequestTransfer())
            ->addProductOffer($productOfferTransfer)
            ->setIsTransactional(true)
            ->setThrowExceptionOnValidation(true);
        $productOfferShipmentTypeCollectionResponseTransfer = $this->getFacade()->saveProductOfferShipmentTypes($productOfferServiceCollectionRequestTransfer);

        /** @var \ArrayObject<array-key, \Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers */
        $productOfferTransfers = $productOfferShipmentTypeCollectionResponseTransfer->getProductOffers();

        return $productOfferTransfers->getIterator()->current();
    }
}
