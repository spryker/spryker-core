<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Spryker\Shared\PriceProductOffer\PriceProductOfferConfig;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionQueryCriteriaPluginInterface;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceDimensionUnconditionalQueryCriteriaPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOffer\Persistence\PriceProductOfferRepositoryInterface getRepository()
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOffer\Communication\PriceProductOfferCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 */
class PriceProductOfferPriceDimensionQueryCriteriaPlugin extends AbstractPlugin implements PriceDimensionQueryCriteriaPluginInterface, PriceDimensionUnconditionalQueryCriteriaPluginInterface
{
    /**
     * {@inheritDoc}
     * - Builds price dimension query criteria for product offer prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer|null
     */
    public function buildPriceDimensionQueryCriteria(PriceProductCriteriaTransfer $priceProductCriteriaTransfer): ?QueryCriteriaTransfer
    {
        return $this->getRepository()->createQueryCriteriaTransfer();
    }

    /**
     * {@inheritDoc}
     * - Builds price dimension query criteria for product offer prices.
     *
     * @api
     *
     * @return \Generated\Shared\Transfer\QueryCriteriaTransfer
     */
    public function buildUnconditionalPriceDimensionQueryCriteria(): QueryCriteriaTransfer
    {
        return $this->getRepository()->createQueryCriteriaTransfer();
    }

    /**
     * {@inheritDoc}
     * - Returns product offer dimension.
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
