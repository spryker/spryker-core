<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Form\Plugin\Application;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\ApplicationExtension\Dependency\Plugin\ApplicationPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory;

/**
 * @method \Spryker\Yves\Form\FormFactory getFactory()
 * @method \Spryker\Yves\Form\FormConfig getConfig()
 */
class FormApplicationPlugin extends AbstractPlugin implements ApplicationPluginInterface
{
    public const SERVICE_FORM_FACTORY = 'form.factory';
    public const SERVICE_FORM_RESOLVED_TYPE_FACTORY = 'form.resolved_type_factory';

    public const SERVICE_FORM_FACTORY_ALIAS = 'FORM_FACTORY';

    /**
     * {@inheritdoc}
     * - Adds `form.resolved_type_factory` service.
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
        $container->set(static::SERVICE_FORM_RESOLVED_TYPE_FACTORY, function (ContainerInterface $container) {
            return new ResolvedFormTypeFactory();
        });

        $container->set(static::SERVICE_FORM_FACTORY, function (ContainerInterface $container) {
            $formFactoryBuilder = $this->getFactory()->createFormFactoryBuilder()
                ->setResolvedTypeFactory($container->get('form.resolved_type_factory'));

            $formFactoryBuilder = $this->extendForm($formFactoryBuilder, $container);

            return $formFactoryBuilder->getFormFactory();
        });

        $container->setGlobal(static::SERVICE_FORM_FACTORY_ALIAS, $container->get(static::SERVICE_FORM_FACTORY));

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
        foreach ($this->getFactory()->getFormExtensionPlugins() as $formExtensionPlugin) {
            $formFactoryBuilder = $formExtensionPlugin->extend($formFactoryBuilder, $container);
        }

        return $formFactoryBuilder;
    }
}
