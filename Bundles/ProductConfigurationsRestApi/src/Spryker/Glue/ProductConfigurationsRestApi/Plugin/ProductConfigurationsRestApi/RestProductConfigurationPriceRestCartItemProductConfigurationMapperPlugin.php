<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApi\Plugin\ProductConfigurationsRestApi;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;
use Spryker\Glue\Kernel\AbstractPlugin;
use Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin\RestCartItemProductConfigurationMapperPluginInterface;

/**
 * @method \Spryker\Glue\ProductConfigurationsRestApi\ProductConfigurationsRestApiFactory getFactory()
 */
class RestProductConfigurationPriceRestCartItemProductConfigurationMapperPlugin extends AbstractPlugin implements RestCartItemProductConfigurationMapperPluginInterface
{
    /**
     * {@inheritDoc}
     * - Maps product configuration volume price data to `RestCartItemProductConfigurationInstanceAttributesTransfer`.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer
     * @param \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer
     */
    public function map(
        ProductConfigurationInstanceTransfer $productConfigurationInstanceTransfer,
        RestCartItemProductConfigurationInstanceAttributesTransfer $restCartItemProductConfigurationInstanceAttributesTransfer
    ): RestCartItemProductConfigurationInstanceAttributesTransfer {
        return $this->getFactory()
            ->createProductConfigurationPriceProductVolumeMapper()
            ->mapProductConfigurationInstanceTransferToRestCartItemProductConfigurationInstanceAttributesTransfer(
                $productConfigurationInstanceTransfer,
                $restCartItemProductConfigurationInstanceAttributesTransfer
            );
    }
}
