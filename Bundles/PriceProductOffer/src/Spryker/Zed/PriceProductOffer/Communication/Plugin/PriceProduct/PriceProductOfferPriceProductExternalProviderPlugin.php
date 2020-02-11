<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOffer\Communication\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceProductExtension\Dependency\Plugin\PriceProductExternalProviderPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOffer\Business\PriceProductOfferFacadeInterface getFacade()
 * @method \Spryker\Zed\PriceProductOffer\PriceProductOfferConfig getConfig()
 */
class PriceProductOfferPriceProductExternalProviderPlugin extends AbstractPlugin implements PriceProductExternalProviderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Provides product offer prices.
     *
     * @api
     *
     * @param string[] $skus
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function providePriceProductTransfers(array $skus, PriceProductCriteriaTransfer $priceProductCriteriaTransfer): array
    {
        return $this->getFacade()
            ->getPriceProductTransfers($skus, $priceProductCriteriaTransfer);
    }
}
