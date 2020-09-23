<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesProductConfigurationGui\Communication;

use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\SalesProductConfigurationGui\Communication\Resolver\ProductConfigurationTemplateResolver;
use Spryker\Zed\SalesProductConfigurationGui\Communication\Resolver\ProductConfigurationTemplateResolverInterface;
use Spryker\Zed\SalesProductConfigurationGui\SalesProductConfigurationGuiDependencyProvider;

/**
 * @method \Spryker\Zed\SalesProductConfigurationGui\SalesProductConfigurationGuiConfig getConfig()
 */
class SalesProductConfigurationGuiCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Spryker\Zed\SalesProductConfigurationGui\Communication\Resolver\ProductConfigurationTemplateResolverInterface
     */
    public function createProductConfigurationTemplateResolver(): ProductConfigurationTemplateResolverInterface
    {
        return new ProductConfigurationTemplateResolver($this->getProductConfigurationRenderStrategyPlugins());
    }

    /**
     * @return \Spryker\Zed\SalesProductConfigurationGuiExtension\Dependency\Plugin\ProductConfigurationRenderPluginInterface[]
     */
    public function getProductConfigurationRenderStrategyPlugins(): array
    {
        return $this->getProvidedDependency(SalesProductConfigurationGuiDependencyProvider::PLUGINS_PRODUCT_CONFIGURATION_RENDER_STRATEGY);
    }
}
