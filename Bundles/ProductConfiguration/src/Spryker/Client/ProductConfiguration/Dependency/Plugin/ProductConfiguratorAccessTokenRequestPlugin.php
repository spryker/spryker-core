<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Dependency\Plugin;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestDataTransfer;
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
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface[]
     */
    protected $productConfiguratorRequestExpanderPlugins;

    /**
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestExpanderInterface [] $productConfiguratorRequestExpanderPlugins
     */
    public function __construct(array $productConfiguratorRequestExpanderPlugins)
    {
        $this->productConfiguratorRequestExpanderPlugins = $productConfiguratorRequestExpanderPlugins;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        $productConfiguratorRequestTransfer = $this->executeProductConfigurationRequestExpanderPlugins(
            $productConfiguratorRequestTransfer
        );

        $productConfiguratorRequestTransfer->requireAccessTokenRequestUrl();

        return (new ProductConfiguratorRedirectTransfer())
            ->setIsSuccessful(true)
            ->setConfiguratorRedirectUrl('url');
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer
     */
    protected function executeProductConfigurationRequestExpanderPlugins(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRequestTransfer {
        foreach ($this->productConfiguratorRequestExpanderPlugins as $productConfiguratorRequestExpanderPlugin) {
            $productConfiguratorRequestTransfer = $productConfiguratorRequestExpanderPlugin->expand($productConfiguratorRequestTransfer);
        }

        return $productConfiguratorRequestTransfer;
    }
}
