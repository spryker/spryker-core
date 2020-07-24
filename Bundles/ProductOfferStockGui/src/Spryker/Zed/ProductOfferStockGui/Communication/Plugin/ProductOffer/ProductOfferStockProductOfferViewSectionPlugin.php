<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferStockGui\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferGuiExtension\Dependency\Plugin\ProductOfferViewSectionPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferStockGui\ProductOfferStockGuiConfig getConfig()
 * @method \Spryker\Zed\ProductOfferStockGui\Communication\ProductOfferStockGuiCommunicationFactory getFactory()
 */
class ProductOfferStockProductOfferViewSectionPlugin extends AbstractPlugin implements ProductOfferViewSectionPluginInterface
{
    /**
     * {@inheritDoc}
     * - Returns template for render product offer stock information.
     *
     * @api
     *
     * @return string
     */
    public function getTemplate(): string
    {
        return '@ProductOfferStockGui/View/_partials/info-product-offer-stock.twig';
    }

    /**
     * {@inheritDoc}
     * - Returns product offer stock data from provided ProductOffer transfer object.
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
            ->createProductOfferStockReader()
            ->getProductOfferStockData($productOfferTransfer);
    }
}
