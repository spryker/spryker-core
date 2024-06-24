<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionDataExport;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionAmountQuery;
use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantCommissionDataExport\Dependency\Facade\MerchantCommissionDataExportToMerchantCommissionFacadeBridge;
use Spryker\Zed\MerchantCommissionDataExport\Dependency\Service\MerchantCommissionDataExportToDataExportServiceBridge;

/**
 * @method \Spryker\Zed\MerchantCommissionDataExport\MerchantCommissionDataExportConfig getConfig()
 */
class MerchantCommissionDataExportDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MERCHANT_COMMISSION = 'FACADE_MERCHANT_COMMISSION';

    /**
     * @var string
     */
    public const SERVICE_DATA_EXPORT = 'SERVICE_DATA_EXPORT';

    /**
     * @var string
     */
    public const PROPEL_QUERY_MERCHANT_COMMISSION = 'PROPEL_QUERY_MERCHANT_COMMISSION';

    /**
     * @var string
     */
    public const PROPEL_QUERY_MERCHANT_COMMISSION_AMOUNT = 'PROPEL_QUERY_MERCHANT_COMMISSION_AMOUNT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMerchantCommissionFacade($container);
        $container = $this->addDataExportService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantCommissionFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_COMMISSION, function (Container $container) {
            return new MerchantCommissionDataExportToMerchantCommissionFacadeBridge(
                $container->getLocator()->merchantCommission()->facade(),
            );
        });

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
        $container = $this->addMerchantCommissionPropelQuery($container);
        $container = $this->addMerchantCommissionAmountPropelQuery($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addDataExportService(Container $container): Container
    {
        $container->set(static::SERVICE_DATA_EXPORT, function (Container $container) {
            return new MerchantCommissionDataExportToDataExportServiceBridge(
                $container->getLocator()->dataExport()->service(),
            );
        });

        return $container;
    }

    /**
     * @module MerchantCommission
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantCommissionPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_COMMISSION, $container->factory(function () {
            return SpyMerchantCommissionQuery::create();
        }));

        return $container;
    }

    /**
     * @module MerchantCommission
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantCommissionAmountPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_MERCHANT_COMMISSION_AMOUNT, $container->factory(function () {
            return SpyMerchantCommissionAmountQuery::create();
        }));

        return $container;
    }
}
