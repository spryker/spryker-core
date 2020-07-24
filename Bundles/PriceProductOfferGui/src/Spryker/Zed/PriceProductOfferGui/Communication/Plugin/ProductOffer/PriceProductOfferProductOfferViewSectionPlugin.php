<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferViewSectionPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferGui\Communication\PriceProductOfferGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductOfferGui\PriceProductOfferGuiConfig getConfig()
 */
class PriceProductOfferProductOfferViewSectionPlugin extends AbstractPlugin implements ProductOfferViewSectionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns template for render price product offer information.
     *
     * @api
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return '@PriceProductOfferGui/View/_partials/info-price-product-offer.twig';
    }

    /**
     * {@inheritDoc}
     * - Returns prices data from provided ProductOffer transfer object.
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
        return $this->getFactory()
            ->createPriceProductOfferReader()
            ->getProductOfferPricesData($productOfferTransfer);
    }
}
