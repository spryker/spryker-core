<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferValidityGui\Communication\Plugin\ProductOfferGui;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferViewSectionPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferValidityGui\ProductOfferValidityGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferValidityGui\Communication\ProductOfferValidityGuiCommunicationFactory getFactory()
 */
class ProductOfferValidityProductOfferViewSectionPlugin extends AbstractPlugin implements ProductOfferViewSectionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns template to rendering product offer validity info.
     *
     * @api
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return '@ProductOfferValidityGui/_partials/info-product-offer-validity.twig';
    }

    /**
     * {@inheritDoc}
     * - Returns product offer validity data from provided ProductOffer transfer.
     *
     * @api
     *
     * @phpstan-return array<mixed>
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return array
     */
    public function getData(ProductOfferTransfer $productOfferTransfer): array
    {
        if (!$productOfferTransfer->getProductOfferValidity()) {
            return [];
        }

        return $productOfferTransfer->getProductOfferValidity()->toArray();
    }
}
