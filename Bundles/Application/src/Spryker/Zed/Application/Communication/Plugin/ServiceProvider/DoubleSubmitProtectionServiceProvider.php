<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Application\Communication\Plugin\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension as DoubleSubmitProtectionExtension;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Application\ApplicationConfig getConfig()
 * @method \Spryker\Zed\Application\Business\ApplicationFacadeInterface getFacade()
 * @method \Spryker\Zed\Application\Communication\ApplicationCommunicationFactory getFactory()
 */
class DoubleSubmitProtectionServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app->extend('form.extensions', function ($extensions) use ($app) {
            $extensions[] = $this->createDoubleSubmitProtectionExtension($app);

            return $extensions;
        });
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension
     */
    protected function createDoubleSubmitProtectionExtension(ContainerInterface $container)
    {
        $translator = $container->has('translator') ? $container->get('translator') : null;

        return new DoubleSubmitProtectionExtension(
            $this->createTokenGenerator(),
            $this->createTokenStorage($container),
            $translator
        );
    }

    /**
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator
     */
    protected function createTokenGenerator()
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
        return new SessionStorage($container->get('session'));
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }
}
