<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferGui\Communication\Plugin\ProductMerchantPortalGui;

use Generated\Shared\Transfer\PriceProductTableCriteriaTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\ProductMerchantPortalGuiExtension\Dependency\Plugin\PriceProductTableFilterPluginInterface;

/**
 * @method \Spryker\Zed\PriceProductOfferGui\Communication\PriceProductOfferGuiCommunicationFactory getFactory()
 * @method \Spryker\Zed\PriceProductOfferGui\PriceProductOfferGuiConfig getConfig()
 */
class PriceProductOfferPriceProductTableFilterPlugin extends AbstractPlugin implements PriceProductTableFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Filters out product offer prices from price product collection.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filter(array $priceProductTransfers, PriceProductTableCriteriaTransfer $priceProductTableCriteriaTransfer): array
    {
        return $this->getFactory()
            ->createPriceProductOfferFilter()
            ->filterOutProductOfferPrices($priceProductTransfers);
    }
}
