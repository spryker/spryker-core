<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\CustomerDataChangeRequest;

use Spryker\Yves\CustomerDataChangeRequest\Dependency\Client\CustomerDataChangeRequestToCustomerClientBridge;
use Spryker\Yves\CustomerDataChangeRequest\Dependency\Client\CustomerDataChangeRequestToCustomerClientInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

/**
 * @method \Spryker\Yves\CustomerDataChangeRequest\CustomerDataChangeRequestConfig getConfig()
 */
class CustomerDataChangeRequestDependencyProvider extends AbstractBundleDependencyProvider
{
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
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container): CustomerDataChangeRequestToCustomerClientInterface {
            return new CustomerDataChangeRequestToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }
}
