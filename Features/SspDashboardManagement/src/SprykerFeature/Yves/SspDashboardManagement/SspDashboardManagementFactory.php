<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Yves\SspDashboardManagement;

use Spryker\Client\Customer\CustomerClientInterface;
use Spryker\Client\Store\StoreClientInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Router\Router\RouterInterface;
use SprykerFeature\Client\SspDashboardManagement\SspDashboardManagementClientInterface;

class SspDashboardManagementFactory extends AbstractFactory
{
    /**
     * @return \SprykerFeature\Client\SspDashboardManagement\SspDashboardManagementClientInterface
     */
    public function getSspDashboardManagementClient(): SspDashboardManagementClientInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::CLIENT_DASHBOARD);
    }

    /**
     * @return \Spryker\Client\Customer\CustomerClientInterface
     */
    public function getCustomerClient(): CustomerClientInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \Spryker\Yves\Router\Router\RouterInterface
     */
    public function getRouter(): RouterInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @return \Spryker\Client\Store\StoreClientInterface
     */
    public function getStoreClient(): StoreClientInterface
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::CLIENT_STORE);
    }
}
