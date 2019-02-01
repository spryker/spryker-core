<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\Translator;

use Spryker\Service\Kernel\AbstractBundleDependencyProvider;
use Spryker\Service\Kernel\Container;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;

/**
 * @method \Spryker\Service\Translator\TranslatorConfig getConfig()
 */
class TranslatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const APPLICATION = 'APPLICATION';
    public const STORE = 'STORE';

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    public function provideServiceDependencies(Container $container): Container
    {
        $container = parent::provideServiceDependencies($container);

        $container = $this->addApplication($container);
        $container = $this->addStore($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[static::APPLICATION] = function () {
            return (new Pimple())->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Service\Kernel\Container $container
     *
     * @return \Spryker\Service\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }
}
