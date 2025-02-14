<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDataChangeRequest;

use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToCustomerFacadeBridge;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToGlossaryFacadeBridge;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToMailFacadeBridge;
use Spryker\Zed\CustomerDataChangeRequest\Dependency\CustomerDataChangeRequestToUtilTextServiceBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @method \Spryker\Zed\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getConfig()
 */
class CustomerDataChangeRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_MAIL = 'FACADE_MAIL';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addCustomerFacade($container);
        $container = $this->addMailFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container->set(static::FACADE_CUSTOMER, function (Container $container) {
            return new CustomerDataChangeRequestToCustomerFacadeBridge($container->getLocator()->customer()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new CustomerDataChangeRequestToMailFacadeBridge($container->getLocator()->mail()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addGlossaryFacade(Container $container): Container
    {
        $container->set(static::FACADE_GLOSSARY, function (Container $container) {
            return new CustomerDataChangeRequestToGlossaryFacadeBridge(
                $container->getLocator()->glossary()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_TEXT, function (Container $container) {
            return new CustomerDataChangeRequestToUtilTextServiceBridge($container->getLocator()->utilText()->service());
        });

        return $container;
    }
}
