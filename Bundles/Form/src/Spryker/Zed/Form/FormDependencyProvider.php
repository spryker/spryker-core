<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Form;

use Spryker\Zed\Http\Communication\Pluign\Form\HttpFoundationFormPlugin;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Validator\Communication\Plugin\Form\ValidatorFormPlugin;

/**
 * @method \Spryker\Zed\Form\FormConfig getConfig()
 */
class FormDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_FORM = 'PLUGINS_FORM';
    public const PLUGINS_CORE_FORM = 'PLUGINS_CORE_FORM';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addFormPlugins($container);
        $container = $this->addCoreFormPlugins($container);

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
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCoreFormPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_CORE_FORM, function () {
            return $this->getCoreFormPlugins();
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

    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getCoreFormPlugins(): array
    {
        return [
            new ValidatorFormPlugin(),
            new HttpFoundationFormPlugin(),
        ];
    }
}
