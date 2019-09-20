<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Form\Communication\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormFactoryBuilderInterface;

/**
 * @method \Spryker\Zed\Form\Communication\FormCommunicationFactory getFactory()
 * @method \Spryker\Zed\Form\FormConfig getConfig()
 */
class FormApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    protected const SERVICE_FORM_FACTORY = 'form.factory';
    protected const SERVICE_FORM_FACTORY_ALIAS = 'FORM_FACTORY';

    /**
     * {@inheritdoc}
     * - Adds `form.factory` service.
     * - Adds global `FORM_FACTORY` service as an alias for `form.factory`.
     *
     * @api
     *
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function provide(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addFormFactory($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addFormFactory(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_FORM_FACTORY, function () use ($container) {
            $formFactoryBuilder = $this->getFactory()
                ->createFormFactoryBuilder();

            $formFactoryBuilder = $this->extendForm($formFactoryBuilder, $container);

            return $formFactoryBuilder->getFormFactory();
        });

        $container->configure(static::SERVICE_FORM_FACTORY, ['alias' => static::SERVICE_FORM_FACTORY_ALIAS, 'isGlobal' => true]);

        return $container;
    }

    /**
     * @param \Symfony\Component\Form\FormFactoryBuilderInterface $formFactoryBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\FormFactoryBuilderInterface
     */
    protected function extendForm(FormFactoryBuilderInterface $formFactoryBuilder, ContainerInterface $container): FormFactoryBuilderInterface
    {
        foreach ($this->getFormPlugins() as $formPlugin) {
            $formFactoryBuilder = $formPlugin->extend($formFactoryBuilder, $container);
        }

        return $formFactoryBuilder;
    }

    /**
     * @return \Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface[]
     */
    protected function getFormPlugins(): array
    {
        return $this->getFactory()->getFormPlugins();
    }
}
