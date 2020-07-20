<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication\Plugin\ProductOffer;

use Generated\Shared\Transfer\ProductOfferTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductOfferExtension\Dependency\Plugin\ProductOfferExpanderPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOffer\Communication\PriceProductOfferCommunicationFactory getFactory()
 */
class PriceProductOfferProductOfferExpanderPlugin extends AbstractPlugin implements ProductOfferExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands provided ProductOfferTransfer with PriceProduct transfers.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductOfferTransfer $productOfferTransfer
     *
     * @return \Generated\Shared\Transfer\ProductOfferTransfer
     */
    public function expand(ProductOfferTransfer $productOfferTransfer): ProductOfferTransfer
    {
        return $this->getFacade()->expandProductOfferWithPrices($productOfferTransfer);
    }
}
