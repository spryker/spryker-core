<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Form\Plugin\FormExtension;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

class DoubleSubmitProtectionFormExtensionPlugin implements FormPluginInterface
{
    protected const SERVICE_SESSION = 'session';
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * {@inheritdoc}
     * - Adds DoubleSubmitProtection extension.
     * - Prevents forms from getting submitted twice.
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
            $this->createDoubleSubmitProtectionExtension($container)
        );

        return $formFactoryBuilder;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension
     */
    protected function createDoubleSubmitProtectionExtension(ContainerInterface $container)
    {
        return new DoubleSubmitProtectionExtension(
            $this->createTokenGenerator(),
            $this->createTokenStorage($container),
            $this->getTranslatorService($container)
        );
    }

    /**
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenGeneratorInterface
     */
    protected function createTokenGenerator(): TokenGeneratorInterface
    {
        return new TokenHashGenerator();
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage
     */
    protected function createTokenStorage(ContainerInterface $container)
    {
        return new SessionStorage($container->get(static::SERVICE_SESSION));
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\Translation\TranslatorInterface|null
     */
    protected function getTranslatorService(ContainerInterface $container): ?TranslatorInterface
    {
        return $container->get(static::SERVICE_TRANSLATOR) ?? null;
    }
}
