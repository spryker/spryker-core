<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilSanitize;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;

/**
 * @method \Spryker\Service\UtilSanitize\UtilSanitizeConfig getConfig()
 */
class UtilSanitizeDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_STRING_SANITIZER = 'PLUGINS_STRING_SANITIZER';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container)
    {
        $container = $this->addStringSanitizerPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addStringSanitizerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_STRING_SANITIZER, function () {
            return $this->getStringSanitizerPLugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Service\UtilSanitizeExtension\Dependency\Plugin\StringSanitizerPluginInterface[]
     */
    protected function getStringSanitizerPlugins(): array
    {
        return [];
    }
}
