<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Security\Plugin\FormExtension;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CsrfFormExtensionPlugin implements FormPluginInterface
{
    protected const SERVICE_CSRF_PROVIDER = 'form.csrf_provider';

    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * {@inheritdoc}
     * - Adds CSRF extension to forms.
     *
     * @api
     *
     * @param \Symfony\Component\Form\FormFactoryBuilderInterface $formFactoryBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\FormFactoryBuilderInterface
     */
    public function extend(FormFactoryBuilderInterface $formFactoryBuilder, ContainerInterface $container): FormFactoryBuilderInterface
    {
        $formFactoryBuilder->addExtension(
            $this->createCsrfExtension($container)
        );

        return $formFactoryBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Form\Extension\Csrf\CsrfExtension
     */
    protected function createCsrfExtension(ContainerInterface $container): CsrfExtension
    {
        return new CsrfExtension(
            $container->get(static::SERVICE_CSRF_PROVIDER),
            $this->getTranslatorService($container)
        );
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|null
     */
    protected function getTranslatorService(ContainerInterface $container): ?TranslatorInterface
    {
        return $container->has(static::SERVICE_TRANSLATOR) ? $container->get(static::SERVICE_TRANSLATOR) : null;
    }
}
