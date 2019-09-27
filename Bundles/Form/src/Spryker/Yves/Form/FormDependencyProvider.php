<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Form;

use Spryker\Yves\Http\Plugin\Form\HttpFoundationTypeExtensionFormPlugin;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Validator\Plugin\Form\ValidatorExtensionFormPlugin;

/**
 * @method \Spryker\Yves\Form\FormConfig getConfig()
 */
class FormDependencyProvider extends AbstractBundleDependencyProvider
{
    public const PLUGINS_FORM = 'PLUGINS_FORM';
    public const PLUGINS_CORE_FORM = 'PLUGINS_CORE_FORM';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addFormPlugins($container);
        $container = $this->addCoreFormPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addFormPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_FORM, function () {
            return $this->getFormPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
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
            new ValidatorExtensionFormPlugin(),
            new HttpFoundationTypeExtensionFormPlugin(),
        ];
    }
}
