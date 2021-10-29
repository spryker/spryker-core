<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Use this plugin to map additional data from `RestProductConfigurationPriceAttributesTransfer[]` to `ProductConfigurationInstanceTransfer`.
 */
interface ProductConfigurationPriceMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `RestProductConfigurationPriceAttributesTransfer[]` to `ProductConfigurationInstanceTransfer`.
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
    ): ProductConfigurationInstanceTransfer;
}
