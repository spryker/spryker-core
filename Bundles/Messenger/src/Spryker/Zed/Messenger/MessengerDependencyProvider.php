<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Messenger;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Messenger\Communication\Plugin\TranslationPlugin;

/**
 * @method \Spryker\Zed\Messenger\MessengerConfig getConfig()
 */
class MessengerDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SESSION = 'session';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @deprecated See \Spryker\Zed\Messenger\MessengerDependencyProvider::PLUGINS_TRANSLATION
     */
    public const PLUGIN_TRANSLATION = 'translation plugin';
    public const PLUGINS_TRANSLATION = 'PLUGINS_TRANSLATION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addSession($container);
        $container = $this->addTranslationPlugin($container);
        $container = $this->addTranslationPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSession(Container $container)
    {
        $container->set(static::SESSION, function (Container $container) {
            /** @var \Symfony\Component\HttpFoundation\RequestStack $requestStack */
            $requestStack = $container->getApplicationService(static::SERVICE_REQUEST_STACK);

            return $requestStack->getCurrentRequest()->getSession();
        });

        return $container;
    }

    /**
     * @deprecated See \Spryker\Zed\Messenger\MessengerDependencyProvider::addTranslationPlugins
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslationPlugin(Container $container)
    {
        $container->set(static::PLUGIN_TRANSLATION, function (Container $container) {
            return new TranslationPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addTranslationPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_TRANSLATION, function () {
            return $this->getTranslationPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\MessengerExtension\Dependency\Plugin\TranslationPluginInterface[]
     */
    protected function getTranslationPlugins(): array
    {
        return [];
    }
}
