<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfigurationExtension\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;

/**
 * Use this plugin to extend product configurator request.
 */
interface ProductConfiguratorRequestExpanderInterface
{
    /**
     * Specification:
     * - Expands product configurator request with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    public function expand(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer;
}
