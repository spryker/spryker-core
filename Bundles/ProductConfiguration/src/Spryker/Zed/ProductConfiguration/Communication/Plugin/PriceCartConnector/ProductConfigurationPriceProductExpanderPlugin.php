<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductConfiguration\Communication\Plugin\PriceCartConnector;

use Generated\Shared\Transfer\CartChangeTransfer;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\PriceCartConnectorExtension\Dependency\Plugin\PriceProductExpanderPluginInterface;

/**
 * @method \Spryker\Zed\ProductConfiguration\Business\ProductConfigurationFacadeInterface getFacade()
 * @method \Spryker\Zed\ProductConfiguration\Communication\ProductConfigurationCommunicationFactory getFactory()
 * @method \Spryker\Zed\ProductConfiguration\ProductConfigurationConfig getConfig()
 */
class ProductConfigurationPriceProductExpanderPlugin extends AbstractPlugin implements PriceProductExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Expands the list of product price transfers with product configuration prices.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param \Generated\Shared\Transfer\CartChangeTransfer $cartChangeTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function expandPriceProductTransfers(
        array $priceProductTransfers,
        CartChangeTransfer $cartChangeTransfer
    ): array {
        return $this->getFacade()->expandPriceProductTransfersWithProductConfigurationPrices(
            $priceProductTransfers,
            $cartChangeTransfer
        );
    }
}
