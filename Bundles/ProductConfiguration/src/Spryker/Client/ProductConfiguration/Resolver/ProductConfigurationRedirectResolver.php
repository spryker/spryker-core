<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface;

class ProductConfigurationRedirectResolver implements ProductConfigurationRedirectResolverInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface[]
     */
    protected $productConfiguratorRequestPlugins;
    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface
     */
    protected $productConfiguratorRequestDefaultPlugin;

    /**
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface[] $productConfiguratorRequestPlugins
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface $productConfiguratorRequestDefaultPlugin
     */
    public function __construct(
        array $productConfiguratorRequestPlugins,
        ProductConfigurationExtensionRequestPluginInterface $productConfiguratorRequestDefaultPlugin
    ) {
        $this->productConfiguratorRequestPlugins = $productConfiguratorRequestPlugins;
        $this->productConfiguratorRequestDefaultPlugin = $productConfiguratorRequestDefaultPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function resolveProductConfiguratorRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        foreach ($this->productConfiguratorRequestPlugins as $key => $productConfiguratorRequestPlugin) {
            if ($key === $productConfiguratorRequestTransfer->getProductConfiguratorRequestData()->getConfiguratorKey()) {
                return $productConfiguratorRequestPlugin
                    ->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
            }
        }

        return $this->productConfiguratorRequestDefaultPlugin
            ->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
    }
}
