<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationWishlistsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;

/**
 * Use this plugin to map additional data from `ProductConfigurationInstanceTransfer` to `RestProductConfigurationPriceAttributesTransfer[]`.
 */
interface RestProductConfigurationPriceMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `ProductConfigurationInstanceTransfer` to `RestProductConfigurationPriceAttributesTransfer[]`.
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
    ): array;
}
