<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SessionUserValidation;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SessionUserValidation\Dependency\Facade\SessionUserValidationToUserFacadeBridge;
use Spryker\Zed\SessionUserValidation\Exception\MissingSessionUserSaverPluginException;
use Spryker\Zed\SessionUserValidation\Exception\MissingSessionUserValidatorPluginException;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface;
use Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface;

/**
 * @method \Spryker\Zed\SessionUserValidation\SessionUserValidationConfig getConfig()
 */
class SessionUserValidationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_SESSION_USER_SAVER = 'PLUGIN_SESSION_USER_SAVER';

    /**
     * @var string
     */
    public const PLUGIN_SESSION_USER_VALIDATOR = 'PLUGIN_SESSION_USER_VALIDATOR';

    /**
     * @var string
     */
    public const FACADE_USER = 'FACADE_USER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addSessionUserSaverPlugin($container);
        $container = $this->addSessionUserValidatorPlugin($container);
        $container = $this->addUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionUserSaverPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SESSION_USER_SAVER, function () {
            return $this->getSessionUserSaverPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Zed\SessionUserValidation\Exception\MissingSessionUserSaverPluginException
     *
     * @return \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserSaverPluginInterface
     */
    protected function getSessionUserSaverPlugin(): SessionUserSaverPluginInterface
    {
        throw new MissingSessionUserSaverPluginException(
            sprintf(
                'Missing instance of %s! You need to configure SessionUserSaverPlugin ' .
                'in your own %s::%s() ' .
                'to be able to save session for user.',
                SessionUserSaverPluginInterface::class,
                static::class,
                __METHOD__,
            ),
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSessionUserValidatorPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SESSION_USER_VALIDATOR, function () {
            return $this->getSessionUserValidatorPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Zed\SessionUserValidation\Exception\MissingSessionUserValidatorPluginException
     *
     * @return \Spryker\Zed\SessionUserValidationExtension\Dependency\Plugin\SessionUserValidatorPluginInterface
     */
    protected function getSessionUserValidatorPlugin(): SessionUserValidatorPluginInterface
    {
        throw new MissingSessionUserValidatorPluginException(
            sprintf(
                'Missing instance of %s! You need to configure SessionUserValidatorPlugin ' .
                'in your own %s::%s() ' .
                'to be able to validate session for user.',
                SessionUserSaverPluginInterface::class,
                static::class,
                __METHOD__,
            ),
        );
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_USER, function (Container $container) {
            return new SessionUserValidationToUserFacadeBridge(
                $container->getLocator()->user()->facade(),
            );
        });

        return $container;
    }
}
