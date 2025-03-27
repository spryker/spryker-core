<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeature\Zed\SspDashboardManagement\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use SprykerFeature\Zed\SspDashboardManagement\Business\Reader\DashboardReader;
use SprykerFeature\Zed\SspDashboardManagement\Business\Reader\DashboardReaderInterface;
use SprykerFeature\Zed\SspDashboardManagement\SspDashboardManagementDependencyProvider;

/**
 * @method \SprykerFeature\Zed\SspDashboardManagement\SspDashboardManagementConfig getConfig()
 */
class SspDashboardManagementBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \SprykerFeature\Zed\SspDashboardManagement\Business\Reader\DashboardReaderInterface
     */
    public function createDashboardReader(): DashboardReaderInterface
    {
        return new DashboardReader($this->getDashboardDataProviderPlugins());
    }

    /**
     * @return array<int, \SprykerFeature\Zed\SspDashboardManagement\Dependency\Plugin\DashboardDataProviderPluginInterface>
     */
    public function getDashboardDataProviderPlugins(): array
    {
        return $this->getProvidedDependency(SspDashboardManagementDependencyProvider::PLUGINS_DASHBOARD_DATA_PROVIDER);
    }
}
