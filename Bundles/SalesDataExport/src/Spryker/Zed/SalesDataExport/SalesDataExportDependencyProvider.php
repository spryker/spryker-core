<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesDataExport;

use Orm\Zed\Sales\Persistence\SpySalesExpenseQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderItemQuery;
use Orm\Zed\Sales\Persistence\SpySalesOrderQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceBridge;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToDataExportServiceInterface;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToUtilEncodingServiceBridge;
use Spryker\Zed\SalesDataExport\Dependency\Service\SalesDataExportToUtilEncodingServiceInterface;

/**
 * @method \Spryker\Zed\SalesDataExport\SalesDataExportConfig getConfig()
 */
class SalesDataExportDependencyProvider extends AbstractBundleDependencyProvider
{
    public const SERVICE_DATA_EXPORT = 'SERVICE_DATA_EXPORT';
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    public const PROPEL_QUERY_SALES_ORDER = 'PROPEL_QUERY_SALES_ORDER';
    public const PROPEL_QUERY_SALES_ORDER_ITEM = 'PROPEL_QUERY_SALES_ORDER_ITEM';
    public const PROPEL_QUERY_SALES_EXPENSE = 'PROPEL_QUERY_SALES_EXPENSE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addDataExportService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);
        $container = $this->addSalesOrderPropelQuery($container);
        $container = $this->addSalesOrderItemPropelQuery($container);
        $container = $this->addSalesExpensePropelQuery($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER, $container->factory(function (): SpySalesOrderQuery {
            return SpySalesOrderQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesOrderItemPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_ORDER_ITEM, $container->factory(function (): SpySalesOrderItemQuery {
            return SpySalesOrderItemQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addSalesExpensePropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_SALES_EXPENSE, $container->factory(function (): SpySalesExpenseQuery {
            return SpySalesExpenseQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container): SalesDataExportToUtilEncodingServiceInterface {
            return new SalesDataExportToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataExportService(Container $container): Container
    {
        $container->set(static::SERVICE_DATA_EXPORT, function (Container $container): SalesDataExportToDataExportServiceInterface {
            return new SalesDataExportToDataExportServiceBridge(
                $container->getLocator()->dataExport()->service()
            );
        });

        return $container;
    }
}
