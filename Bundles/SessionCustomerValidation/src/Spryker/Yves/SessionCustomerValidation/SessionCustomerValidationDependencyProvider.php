<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\SessionCustomerValidation;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\SessionCustomerValidation\Dependency\Client\SessionCustomerValidationToCustomerClientBridge;
use Spryker\Yves\SessionCustomerValidation\Exception\MissingSessionCustomerSaverPluginException;
use Spryker\Yves\SessionCustomerValidation\Exception\MissingSessionCustomerValidatorPluginException;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface;
use Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface;

class SessionCustomerValidationDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGIN_SESSION_CUSTOMER_SAVER = 'PLUGIN_SESSION_USER_SAVER';

    /**
     * @var string
     */
    public const PLUGIN_SESSION_CUSTOMER_VALIDATOR = 'PLUGIN_SESSION_USER_VALIDATOR';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addSessionCustomerSaverPlugin($container);
        $container = $this->addSessionCustomerValidatorPlugin($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionCustomerSaverPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SESSION_CUSTOMER_SAVER, function () {
            return $this->getSessionCustomerSaverPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Yves\SessionCustomerValidation\Exception\MissingSessionCustomerSaverPluginException
     *
     * @return \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerSaverPluginInterface
     */
    protected function getSessionCustomerSaverPlugin(): SessionCustomerSaverPluginInterface
    {
        throw new MissingSessionCustomerSaverPluginException(
            sprintf(
                'Missing instance of %s! You need to configure SessionCustomerSaverPlugin ' .
                'in your own %s::%s() ' .
                'to be able to save session for customer.',
                SessionCustomerSaverPluginInterface::class,
                static::class,
                __METHOD__,
            ),
        );
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionCustomerValidatorPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_SESSION_CUSTOMER_VALIDATOR, function () {
            return $this->getSessionCustomerValidatorPlugin();
        });

        return $container;
    }

    /**
     * @throws \Spryker\Yves\SessionCustomerValidation\Exception\MissingSessionCustomerValidatorPluginException
     *
     * @return \Spryker\Yves\SessionCustomerValidationExtension\Dependency\Plugin\SessionCustomerValidatorPluginInterface
     */
    protected function getSessionCustomerValidatorPlugin(): SessionCustomerValidatorPluginInterface
    {
        throw new MissingSessionCustomerValidatorPluginException(
            sprintf(
                'Missing instance of %s! You need to configure SessionCustomerValidatorPlugin ' .
                'in your own %s::%s() ' .
                'to be able to validate session for customer.',
                SessionCustomerValidatorPluginInterface::class,
                static::class,
                __METHOD__,
            ),
        );
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new SessionCustomerValidationToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }
}
