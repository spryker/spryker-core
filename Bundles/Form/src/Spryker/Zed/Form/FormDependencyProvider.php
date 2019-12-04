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
    public const PLUGINS_FORM = 'PLUGINS_FORM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFormPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FORM, function () {
            return $this->getFormPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getFormPlugins(): array
    {
        return [];
    }
}
