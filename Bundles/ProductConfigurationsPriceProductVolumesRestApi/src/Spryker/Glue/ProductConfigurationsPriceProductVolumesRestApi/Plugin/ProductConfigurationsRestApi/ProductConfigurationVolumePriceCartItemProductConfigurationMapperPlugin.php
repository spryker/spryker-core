<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\Plugin\ProductConfigurationsRestApi;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\CartItemProductConfigurationMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationsPriceProductVolumesRestApi\ProductConfigurationsPriceProductVolumesRestApiFactory getFactory()
 */
class ProductConfigurationVolumePriceCartItemProductConfigurationMapperPlugin extends AbstractPlugin implements CartItemProductConfigurationMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps product configuration volume price data to `ProductConfigurationInstanceTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer
     */
    public function map(
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer,
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
    ): ProductConfigurationInstanceTransfer {
        return $this->getFactory()
            ->createRestCartItemProductConfigurationPriceProductVolumeMapper()
            ->mapRestCartItemProductConfigurationInstanceAttributesToProductConfigurationInstance(
                $restCartItemProductConfigurationInstanceAttributesTransfer,
                $productConfigurationInstanceTransfer
            );
    }
}
