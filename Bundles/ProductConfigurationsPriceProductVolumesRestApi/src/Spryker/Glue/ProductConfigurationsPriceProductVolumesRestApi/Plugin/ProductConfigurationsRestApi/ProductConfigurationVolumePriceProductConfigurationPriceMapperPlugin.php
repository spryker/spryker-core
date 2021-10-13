<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin\ProductConfigurationsRestApi;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\ProductConfigurationPriceMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\ProductConfigurationsPriceProductVolumesRestApiFactory getFactory()
 */
class ProductConfigurationVolumePriceProductConfigurationPriceMapperPlugin extends AbstractPlugin implements ProductConfigurationPriceMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps product configuration volume price data to `ProductConfigurationInstanceTransfer`.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\RestProductConfigurationPriceAttributesTransfer> $restProductConfigurationPriceAttributesTransfers
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function map(
        array $restProductConfigurationPriceAttributesTransfers,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        return $this->getFactory()
            ->createRestProductConfigurationPriceProductVolumeMapper()
            ->mapRestProductConfigurationPriceAttributesToProductConfigurationInstance(
                $restProductConfigurationPriceAttributesTransfers,
                $productConfigurationInstanceTransfer
            );
    }
}
