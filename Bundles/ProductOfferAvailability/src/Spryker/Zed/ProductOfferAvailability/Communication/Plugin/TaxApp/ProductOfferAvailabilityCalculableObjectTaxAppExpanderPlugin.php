<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferAvailability\Communication\Plugin\TaxApp;

use Generated\Shared\Transfer\CalculableObjectTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\TaxAppExtension\Dependency\Plugin\CalculableObjectTaxAppExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductOfferAvailability\Business\ProductOfferAvailabilityFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductOfferAvailability\ProductOfferAvailabilityConfig getConfig()
 */
class ProductOfferAvailabilityCalculableObjectTaxAppExpanderPlugin extends AbstractPlugin implements CalculableObjectTaxAppExpanderPluginInterface
{
 /**
  * {@inheritDoc}
  * - Expands `CalculableObject.Item.merchantStockAddresses` with colletion of `MerchantStockAddress` split by quantity to ship from each stock.
  *
  * @api
  *
  * @param \Generated\Shared\Transfer\CalculableObjectTransfer $calculableObjectTransfer
  *
  * @return \Generated\Shared\Transfer\CalculableObjectTransfer
  */
    public function expand(CalculableObjectTransfer $calculableObjectTransfer): CalculableObjectTransfer
    {
        return $this->getFacade()->expandCalculableObjectItemsWithMerchantStockAddressSplitByStockAvailability(
            $calculableObjectTransfer,
        );
    }
}
