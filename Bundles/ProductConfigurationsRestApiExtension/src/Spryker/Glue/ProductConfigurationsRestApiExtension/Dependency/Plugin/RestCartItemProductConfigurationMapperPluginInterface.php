<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductConfigurationsRestApiExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfigurationInstanceTransfer;
use Generated\Shared\Transfer\RestCartItemProductConfigurationInstanceAttributesTransfer;

/**
 * Use this plugin to map additional data from `ProductConfigurationInstanceTransfer` to `RestCartItemProductConfigurationInstanceAttributesTransfer`.
 */
interface RestCartItemProductConfigurationMapperPluginInterface
{
    /**
     * Specification:
     * - Maps the `ProductConfigurationInstanceTransfer` to `RestCartItemProductConfigurationInstanceAttributesTransfer`.
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
    ): RestCartItemProductConfigurationInstanceAttributesTransfer;
}
