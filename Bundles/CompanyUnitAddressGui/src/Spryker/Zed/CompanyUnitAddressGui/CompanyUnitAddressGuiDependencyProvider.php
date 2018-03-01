<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUnitAddressGui;

use Spryker\Zed\CompanyUnitAddressGui\Dependency\QueryContainer\CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUnitAddressGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    const QUERY_CONTAINER_COMPANY_UNIT_ADDRESS = 'COMPANY_UNIT_ADDRESS_GUI:QUERY_CONTAINER_COMPANY_UNIT_ADDRESS';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addCompanyUnitAddressQueryContainer($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUnitAddressQueryContainer(Container $container)
    {
        $container[static::QUERY_CONTAINER_COMPANY_UNIT_ADDRESS] = function (Container $container) {
            return new CompanyUnitAddressGuiToCompanyUnitAddressQueryContainerBridge($container->getLocator()->companyUnitAddress()->queryContainer());
        };

        return $container;
    }
}
