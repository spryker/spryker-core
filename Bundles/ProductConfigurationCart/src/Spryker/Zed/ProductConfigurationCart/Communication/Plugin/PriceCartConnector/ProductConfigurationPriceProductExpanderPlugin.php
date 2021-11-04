<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfigurationCart\Communication\Plugin\PriceCartConnector;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\PriceProductExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfigurationCart\Business\ProductConfigurationCartFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfigurationCart\ProductConfigurationCartConfig getConfig()
 */
class ProductConfigurationPriceProductExpanderPlugin extends AbstractPlugin implements PriceProductExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the list of price product transfers with product configuration prices.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function expandPriceProductTransfers(
        array $priceProductTransfers,
        CartChangeTransfer $cartChangeTransfer
    ): array {
        return $this->getFacade()->expandPriceProductTransfersWithProductConfigurationPrices(
            $priceProductTransfers,
            $cartChangeTransfer,
        );
    }
}
