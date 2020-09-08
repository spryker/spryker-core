<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration\Resolver;

use Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer;
use Generated\Shared\Transfer\ProductConfiguratorRequestTransfer;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface;

class ProductConfiguratorRedirectResolver implements ProductConfiguratorRedirectResolverInterface
{
    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface[]
     */
    protected $productConfiguratorRequestPlugins;

    /**
     * @var \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface
     */
    protected $productConfiguratorRequestDefaultPlugin;

    /**
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface[] $productConfiguratorRequestPlugins
     * @param \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfiguratorRequestPluginInterface $productConfiguratorRequestDefaultPlugin
     */
    public function __construct(
        array $productConfiguratorRequestPlugins,
        ProductConfiguratorRequestPluginInterface $productConfiguratorRequestDefaultPlugin
    ) {
        $this->productConfiguratorRequestPlugins = $productConfiguratorRequestPlugins;
        $this->productConfiguratorRequestDefaultPlugin = $productConfiguratorRequestDefaultPlugin;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConfiguratorRedirectTransfer
     */
    public function prepareProductConfiguratorRedirect(
        ProductConfiguratorRequestTransfer $productConfiguratorRequestTransfer
    ): ProductConfiguratorRedirectTransfer {
        foreach ($this->productConfiguratorRequestPlugins as $configuratorKey => $productConfiguratorRequestPlugin) {
            if (
                $configuratorKey === $productConfiguratorRequestTransfer
                    ->getProductConfiguratorRequestData()->getConfiguratorKey()
            ) {
                return $productConfiguratorRequestPlugin
                    ->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
            }
        }

        return $this->productConfiguratorRequestDefaultPlugin
            ->resolveProductConfiguratorRedirect($productConfiguratorRequestTransfer);
    }
}
