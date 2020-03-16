<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyBusinessUnitSalesConnector;

use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\CompanyBusinessUnitSalesConnector\Dependency\Facade\CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyBusinessUnitSalesConnectorDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';
    public const FACADE_COMPANY_SALES_CONNECTOR = 'FACADE_COMPANY_SALES_CONNECTOR';

    public const PROPEL_QUERY_SALES_ORDER = 'PROPEL_QUERY_SALES_ORDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addSalesOrderPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCompanyBusinessUnitClient($container);
        $container = $this->addCompanySalesConnectorFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitClient(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return new CompanyBusinessUnitSalesConnectorToCompanyBusinessUnitFacadeBridge(
                $container->getLocator()->companyBusinessUnit()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanySalesConnectorFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_SALES_CONNECTOR, function (Container $container) {
            return new CompanyBusinessUnitSalesConnectorToCompanySalesConnectorFacadeBridge(
                $container->getLocator()->companySalesConnector()->facade()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER, $container->factory(function () {
            return SpySalesOrderQuery::create();
        }));

        return $container;
    }
}
