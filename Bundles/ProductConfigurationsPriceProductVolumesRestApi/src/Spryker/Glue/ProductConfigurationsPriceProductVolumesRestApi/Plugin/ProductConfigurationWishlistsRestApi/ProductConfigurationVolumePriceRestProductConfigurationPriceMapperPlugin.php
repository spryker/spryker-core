<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin\ProductConfigurationWishlistsRestApi;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin\RestProductConfigurationPriceMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\ProductConfigurationsPriceProductVolumesRestApiFactory getFactory()
 */
class ProductConfigurationVolumePriceRestProductConfigurationPriceMapperPlugin extends AbstractPlugin implements RestProductConfigurationPriceMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps product configuration volume price data to `RestProductConfigurationPriceAttributesTransfer[]`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param array<\Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer> $restProductConfigurationPriceAttributesTransfers
     *
     * @return array<\Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer>
     */
    public function map(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        array $restProductConfigurationPriceAttributesTransfers
    ): array {
        return $this->getFactory()
            ->createProductConfigurationPriceProductVolumeMapper()
            ->mapProductConfigurationInstanceToRestProductConfigurationPriceAttributes(
                $productConfigurationInstanceTransfer,
                $restProductConfigurationPriceAttributesTransfers,
            );
    }
}
