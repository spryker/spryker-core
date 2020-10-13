<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Plugin;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\Kernel\AbstractPlugin;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;

/**
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationClientInterface getClient()
 * @method \Spryker\Client\ProductConfiguration\ProductConfigurationFactory getFactory()
 */
class ProductConfiguratorAccessTokenRequestPlugin extends AbstractPlugin implements ProductConfiguratorRequestPluginInterface
{
    /**
     * {@inheritDoc}
     * - Builds product configuration redirect from configurator response.
     *
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        return $this->getClient()
            ->resolveProductConfiguratorAccessTokenRedirect($productConfiguratorRequestTransfer);
    }
}
