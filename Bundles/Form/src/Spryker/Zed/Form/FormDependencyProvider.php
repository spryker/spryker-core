<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Form;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\Form\FormConfig getConfig()
 */
class FormDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_FORM_EXTENSION = 'PLUGINS_FORM_EXTENSION';
    public const PLUGINS_FORM_TYPE_EXTENSION = 'PLUGINS_FORM_TYPE_EXTENSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addFormExtensionPlugins($container);
        $container = $this->addFormTypeExtensionPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFormExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FORM_EXTENSION, function (Container $container) {
            return $this->getFormExtensionPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFormTypeExtensionPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FORM_TYPE_EXTENSION, function (Container $container) {
            return $this->getFormTypeExtensionPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getFormExtensionPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getFormTypeExtensionPlugins(): array
    {
        return [];
    }
}
