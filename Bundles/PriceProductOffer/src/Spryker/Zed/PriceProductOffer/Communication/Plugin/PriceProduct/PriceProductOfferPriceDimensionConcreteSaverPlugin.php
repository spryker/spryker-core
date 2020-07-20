<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Spryker\Shared\PriceProductOffer\PriceProductOfferConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionConcreteSaverPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOffer\Communication\PriceProductOfferCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 */
class PriceProductOfferPriceDimensionConcreteSaverPlugin extends AbstractPlugin implements PriceDimensionConcreteSaverPluginInterface
{
    /**
     * {@inheritDoc}
     * - Persists Price Product Offer entities.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function savePrice(PriceProductTransfer $priceProductTransfer): PriceProductTransfer
    {
        return $this->getFacade()->savePriceProductOfferRelation($priceProductTransfer);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getDimensionName(): string
    {
        return PriceProductOfferConfig::DIMENSION_TYPE_PRODUCT_OFFER;
    }
}
