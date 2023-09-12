<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferShipmentTypeGui\Communication\Plugin\ProductOfferGui;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferViewSectionPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferShipmentTypeGui\ProductOfferShipmentTypeGuiConfig getConfig()
 */
class ShipmentTypeProductOfferViewSectionPlugin extends AbstractPlugin implements ProductOfferViewSectionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns template for product offer shipment types data rendering.
     *
     * @api
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return '@ProductOfferShipmentTypeGui/_partials/product-offer-shipment-types.twig';
    }

    /**
     * {@inheritDoc}
     * - Returns product offer shipment types data from provided `ProductOfferTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array<string, mixed>
     */
    public function getData(ProductOfferTransfer $productOfferTransfer): array
    {
        return [
            ProductOfferTransfer::SHIPMENT_TYPES => $productOfferTransfer->getShipmentTypes(),
        ];
    }
}
