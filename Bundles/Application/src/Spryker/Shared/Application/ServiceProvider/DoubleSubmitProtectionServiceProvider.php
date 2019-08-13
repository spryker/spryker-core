<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\ServiceProvider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension as DoubleSubmitProtectionExtension;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage;
use Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @deprecated Use `\Spryker\Zed\Application\Communication\Plugin\ServiceProvider\DoubleSubmitProtectionServiceProvider` instead.
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
        $app['form.extension.double_submit_protection'] = $app->share(function ($app) {
            $translator = isset($app['translator']) ? $app['translator'] : null;

            return $this->createDoubleSubmitProtectionExtension($app, $translator);
        });

        $app->extend('form.extensions', function ($extensions) use ($app) {
            $extensions[] = $app['form.extension.double_submit_protection'];

            return $extensions;
        });
    }

    /**
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\TokenHashGenerator
     */
    protected function createTokenGenerator()
    {
        return new TokenHashGenerator();
    }

    /**
     * @param \Silex\Application $app
     *
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\RequestTokenProvider\SessionStorage
     */
    protected function createTokenStorage(Application $app)
    {
        return new SessionStorage($app['session']);
    }

    /**
     * @param \Silex\Application $app
     * @param \Symfony\Component\Translation\TranslatorInterface|null $translator
     *
     * @return \Spryker\Shared\Symfony\Form\Extension\DoubleSubmitProtection\DoubleSubmitProtectionExtension
     */
    protected function createDoubleSubmitProtectionExtension(Application $app, $translator = null)
    {
        return new DoubleSubmitProtectionExtension(
            $this->createTokenGenerator(),
            $this->createTokenStorage($app),
            $translator
        );
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
