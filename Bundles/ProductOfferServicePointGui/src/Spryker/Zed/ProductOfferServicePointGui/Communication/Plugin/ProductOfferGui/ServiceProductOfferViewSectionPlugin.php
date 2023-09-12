<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferServicePointGui\Communication\Plugin\ProductOfferGui;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferViewSectionPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferServicePointGui\ProductOfferServicePointGuiConfig getConfig()
 */
class ServiceProductOfferViewSectionPlugin extends AbstractPlugin implements ProductOfferViewSectionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns template for product offer services data rendering.
     *
     * @api
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return '@ProductOfferServicePointGui/_partials/product-offer-services.twig';
    }

    /**
     * {@inheritDoc}
     * - Returns product offer services data from provided `ProductOfferTransfer`.
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
            ProductOfferTransfer::SERVICES => $productOfferTransfer->getServices(),
        ];
    }
}
