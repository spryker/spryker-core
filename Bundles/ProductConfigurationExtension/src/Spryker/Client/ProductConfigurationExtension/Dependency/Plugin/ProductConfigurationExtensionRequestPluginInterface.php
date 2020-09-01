<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;

/**
 * Use this plugin to prepare configurator page requests.
 */
interface ProductConfigurationExtensionRequestPluginInterface
{
    /**
     * Specification:
     * - Resolves configurator page redirect information for the provided product configuration.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer): ProductConfiguratorRedirectTransfer;
}
