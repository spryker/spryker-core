<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductDimensionTransfer;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductDimensionExpanderStrategyPluginInterface;
use Spryker\Shared\PriceProductOffer\PriceProductOfferConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOffer\Communication\PriceProductOfferCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 */
class PriceProductOfferPriceProductDimensionExpanderStrategyPlugin extends AbstractPlugin implements PriceProductDimensionExpanderStrategyPluginInterface
{
    /**
     * {@inheritDoc}
     *  - Returns true if price product dimension is offer.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return bool
     */
    public function isApplicable(PriceProductDimensionTransfer $priceProductDimensionTransfer): bool
    {
        return (bool)$priceProductDimensionTransfer->getProductOfferReference();
    }

    /**
     * {@inheritDoc}
     *  - Returns expanded transfer with offer dimension type.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductDimensionTransfer $priceProductDimensionTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductDimensionTransfer
     */
    public function expand(PriceProductDimensionTransfer $priceProductDimensionTransfer): PriceProductDimensionTransfer
    {
        return $priceProductDimensionTransfer->setType(PriceProductOfferConfig::DIMENSION_TYPE_PRODUCT_OFFER);
    }
}
