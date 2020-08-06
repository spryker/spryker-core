<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Form\Plugin\Form;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Form\Extension\Csrf\CsrfExtension;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class CsrfFormPlugin extends AbstractPlugin implements FormPluginInterface
{
    /**
     * @uses \Spryker\Yves\Form\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     */
    protected const SERVICE_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @uses \Spryker\Yves\Translator\Plugin\Application\TranslatorApplicationPlugin::SERVICE_TRANSLATOR
     */
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * {@inheritDoc}
     * - Adds 'Symfony\Component\Form\Extension\Csrf\CsrfExtension` extension.
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
            $this->getTranslator($container)
        );
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|null
     */
    protected function getTranslator(ContainerInterface $container): ?TranslatorInterface
    {
        return $container->has(static::SERVICE_TRANSLATOR) ? $container->get(static::SERVICE_TRANSLATOR) : null;
    }
}
