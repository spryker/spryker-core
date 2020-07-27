<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Bootstrap;

use Spryker\Client\Session\SessionClient;
use Spryker\Glue\GlueApplication\Session\Storage\MockArraySessionStorage;
use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\BundleDependencyProviderResolverAwareTrait;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Kernel\FactoryResolverAwareTrait;
use Spryker\Shared\Application\ApplicationInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class GlueBootstrap
{
    use BundleDependencyProviderResolverAwareTrait;
    use FactoryResolverAwareTrait;

    /**
     * @return \Spryker\Shared\Application\ApplicationInterface
     */
    public function boot(): ApplicationInterface
    {
        $this->setUpSession();

        return $this->getFactory()
            ->createApplication()
            ->boot();
    }

    /**
     * @return void
     */
    protected function setUpSession(): void
    {
        (new SessionClient())->setContainer(
            new Session(
                new MockArraySessionStorage()
            )
        );
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
        $container = $dependencyProvider->provideDependencies($container);

        return $container;
    }
}
