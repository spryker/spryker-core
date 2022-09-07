<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Bootstrap;

use Generated\Shared\Transfer\GlueApiContextTransfer;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Kernel\FactoryResolverAwareTrait;
use Spryker\Shared\Application\ApplicationInterface;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class GlueBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;
    use FactoryResolverAwareTrait;

    /**
     * @param array<string> $glueApplicationBootstrapPluginClassNames
     *
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function boot(array $glueApplicationBootstrapPluginClassNames = []): ApplicationInterface
    {
        $apiApplicationContext = $this->createApiApplicationContext();

        $glueApplicationBootstrapPlugin = $this->getFactory()->createApiApplicationBootstrapResolver($glueApplicationBootstrapPluginClassNames)
            ->resolveApiApplicationBootstrap($apiApplicationContext);

        return $this->getFactory()->createApiApplicationProxy($glueApplicationBootstrapPlugin)->boot();
    }

    /**
     * @param \Spryker\Glue\Kernel\AbstractBundleDependencyProvider $dependencyProvider
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function provideExternalDependencies(
        AbstractBundleDependencyProvider $dependencyProvider,
        Container $container
    ): Container {
        return $dependencyProvider->provideDependencies($container);
    }

    /**
     * @return \Generated\Shared\Transfer\GlueApiContextTransfer
     */
    protected function createApiApplicationContext(): GlueApiContextTransfer
    {
        $apiApplicationContext = new GlueApiContextTransfer();

        $contextExpanderPlugins = $this->getFactory()->getGlueContextExpanderPlugins();
        if ($contextExpanderPlugins) {
            return $this->expandContextWithContextExpanderPlugins($apiApplicationContext, $contextExpanderPlugins);
        }

        return $this->getFactory()->createContextHttpExpander()->expand($apiApplicationContext);
    }

    /**
     * @deprecated Will be removed in the next major. Exists for BC-reason only.
     *
     * @param \Generated\Shared\Transfer\GlueApiContextTransfer $apiApplicationContext
     * @param array<\Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\GlueContextExpanderPluginInterface> $contextExpanderPlugins
     *
     * @return \Generated\Shared\Transfer\GlueApiContextTransfer
     */
    protected function expandContextWithContextExpanderPlugins(
        GlueApiContextTransfer $apiApplicationContext,
        array $contextExpanderPlugins
    ): GlueApiContextTransfer {
        foreach ($contextExpanderPlugins as $contextExpanderPlugin) {
            $apiApplicationContext = $contextExpanderPlugin->expand($apiApplicationContext);
        }

        return $apiApplicationContext;
    }
}
