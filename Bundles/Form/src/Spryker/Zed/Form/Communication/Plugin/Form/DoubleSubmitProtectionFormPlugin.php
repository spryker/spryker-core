<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Form\Communication\Plugin\Form;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Form\DoubleSubmitProtection\DoubleSubmitProtectionExtension;
use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\SessionStorage;
use Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\StorageInterface;
use Spryker\Shared\FormExtension\Dependency\Plugin\FormPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\Form\FormFactoryBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @method \Spryker\Zed\Form\Communication\FormCommunicationFactory getFactory()
 * @method \Spryker\Zed\Form\FormConfig getConfig()
 */
class DoubleSubmitProtectionFormPlugin extends AbstractPlugin implements FormPluginInterface
{
    /**
     * @uses \Spryker\Zed\Session\Communication\Plugin\Application\SessionApplicationPlugin::SERVICE_SESSION
     */
    protected const SERVICE_SESSION = 'session';

    /**
     * @uses \Spryker\Zed\Translator\Communication\Plugin\Application\TranslatorApplicationPlugin::SERVICE_TRANSLATOR
     */
    protected const SERVICE_TRANSLATOR = 'translator';

    /**
     * {@inheritDoc}
     * - Adds `Spryker\Shared\Form\DoubleSubmitProtection\DoubleSubmitProtectionExtension`.
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
     * @return \Spryker\Shared\Form\DoubleSubmitProtection\DoubleSubmitProtectionExtension
     */
    protected function createDoubleSubmitProtectionExtension(ContainerInterface $container): DoubleSubmitProtectionExtension
    {
        return new DoubleSubmitProtectionExtension(
            $this->getFactory()->createTokenGenerator(),
            $this->createTokenStorage($container),
            $this->getTranslatorService($container)
        );
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\Form\DoubleSubmitProtection\RequestTokenProvider\SessionStorage
     */
    protected function createTokenStorage(ContainerInterface $container): StorageInterface
    {
        return new SessionStorage($container->get(static::SERVICE_SESSION));
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Contracts\Translation\TranslatorInterface|null
     */
    protected function getTranslatorService(ContainerInterface $container): ?TranslatorInterface
    {
        return $container->has(static::SERVICE_TRANSLATOR) ? $container->get(static::SERVICE_TRANSLATOR) : null;
    }
}
