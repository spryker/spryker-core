<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\ProductConfiguration\Plugin\PriceProduct;

use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Spryker\Service\Kernel\AbstractPlugin;
use Spryker\Service\PriceProductExtension\Dependency\Plugin\PriceProductFilterPluginInterface;
use Spryker\Shared\ProductConfiguration\ProductConfigurationConfig;

/**
 * @method \Spryker\Service\ProductConfiguration\ProductConfigurationServiceInterface getService()
 */
class ProductConfigurationVolumePriceProductFilterPlugin extends AbstractPlugin implements PriceProductFilterPluginInterface
{
    /**
     * {@inheritDoc}
     * - Compares singular item prices with volume prices.
     * - Finds corresponding volume price for provided quantity.
     * - Returns singular item prices if matching volume price can not be found.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\PriceProductTransfer> $priceProductTransfers
     * @param \Generated\Shared\Transfer\PriceProductFilterTransfer $priceProductFilterTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    public function filter(array $priceProductTransfers, PriceProductFilterTransfer $priceProductFilterTransfer): array
    {
        return $this->getService()->filterProductConfigurationVolumePrices($priceProductTransfers, $priceProductFilterTransfer);
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
        return ProductConfigurationConfig::PRICE_DIMENSION_PRODUCT_CONFIGURATION;
    }
}
