<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Translator;

use Spryker\Shared\Kernel\Store;
use Spryker\Shared\TranslatorExtension\Dependency\Plugin\TranslatorPluginInterface;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Communication\Plugin\Pimple;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Translator\Communication\Plugin\TranslatorPlugin;
use Spryker\Zed\Translator\Dependency\Facade\TranslatorToLocaleFacadeBridge;

/**
 * @method \Spryker\Zed\Translator\TranslatorConfig getConfig()
 */
class TranslatorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_TRANSLATOR = 'SERVICE_TRANSLATOR';
    public const APPLICATION = 'APPLICATION';
    public const STORE = 'STORE';

    public const FACADE_LOCALE = 'FACADE_LOCALE';

    public const PLUGIN_TRANSLATOR = 'PLUGIN_TRANSLATOR';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addApplication($container);
        $container = $this->addStore($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);

        $container = $this->addApplication($container);
        $container = $this->addLocaleFacade($container);
        $container = $this->addTranslatorPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[static::APPLICATION] = function () {
            return (new Pimple())->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addStore(Container $container): Container
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container[static::FACADE_LOCALE] = function (Container $container) {
            return new TranslatorToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslatorPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_TRANSLATOR, function (): TranslatorPluginInterface {
            return $this->getTranslatorPlugin();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\TranslatorExtension\Dependency\Plugin\TranslatorPluginInterface
     */
    protected function getTranslatorPlugin(): TranslatorPluginInterface
    {
        return new TranslatorPlugin();
    }
}
