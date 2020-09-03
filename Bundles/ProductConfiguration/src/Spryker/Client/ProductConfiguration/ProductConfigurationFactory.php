<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductConfiguration;

use Spryker\Client\Kernel\AbstractFactory;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfigurationRedirectResolver;
use Spryker\Client\ProductConfiguration\Resolver\ProductConfigurationRedirectResolverInterface;
use Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface;
use Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationChecker;
use Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationCheckerInterface;

class ProductConfigurationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface[]
     */
    public function getProductConfigurationRequestPlugins(): array
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGINS_PRODUCT_CONFIGURATOR_REQUEST);
    }

    /**
     * @return \Spryker\Client\ProductConfigurationExtension\Dependency\Plugin\ProductConfigurationExtensionRequestPluginInterface
     */
    public function getProductConfiguratorRequestDefaultPlugin(): ProductConfigurationExtensionRequestPluginInterface
    {
        return $this->getProvidedDependency(ProductConfigurationDependencyProvider::PLUGIN_PRODUCT_CONFIGURATOR_REQUEST_DEFAULT);
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Resolver\ProductConfigurationRedirectResolverInterface
     */
    public function createProductConfigurationRedirectResolver(): ProductConfigurationRedirectResolverInterface
    {
        return new ProductConfigurationRedirectResolver(
            $this->getProductConfigurationRequestPlugins(),
            $this->getProductConfiguratorRequestDefaultPlugin()
        );
    }

    /**
     * @return \Spryker\Client\ProductConfiguration\Checker\QuoteProductConfigurationCheckerInterface
     */
    public function createQuoteProductConfigurationChecker(): QuoteProductConfigurationCheckerInterface
    {
        return new QuoteProductConfigurationChecker();
    }
}
