<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\Plugin\ProductOfferMerchantPortalGui;

use Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferMerchantPortalGuiExtension\Dependency\Plugin\ProductOfferFormViewExpanderPluginInterface;
use Symfony\Component\Form\FormView;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\ProductOfferShipmentTypeMerchantPortalGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferShipmentTypeMerchantPortalGui\Communication\ProductOfferShipmentTypeMerchantPortalGuiCommunicationFactory getFactory()
 */
class ShipmentTypeProductOfferFormViewExpanderPlugin extends AbstractPlugin implements ProductOfferFormViewExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands `ProductOfferForm` Twig template with `shipment-type` form section.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormView $formView
     * @param \Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer $productOfferFormViewCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferFormViewCollectionTransfer
     */
    public function expand(
        FormView $formView,
        ProductOfferFormViewCollectionTransfer $productOfferFormViewCollectionTransfer
    ): ProductOfferFormViewCollectionTransfer {
        return $this->getFactory()->createShipmentTypeProductOfferFormViewExpander()->expand(
            $formView,
            $productOfferFormViewCollectionTransfer,
        );
    }
}
