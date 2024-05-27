<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommissionGui;

use Orm\Zed\MerchantCommission\Persistence\SpyMerchantCommissionQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToGlossaryFacadeBridge;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToMerchantCommissionDataExportFacadeBridge;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToMerchantCommissionFacadeBridge;
use Spryker\Zed\MerchantCommissionGui\Dependency\Facade\MerchantCommissionGuiToMoneyFacadeBridge;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilCsvServiceBridge;
use Spryker\Zed\MerchantCommissionGui\Dependency\Service\MerchantCommissionGuiToUtilDateTimeServiceBridge;

/**
 * @method \Spryker\Zed\MerchantCommissionGui\MerchantCommissionGuiConfig getConfig()
 */
class MerchantCommissionGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MERCHANT_COMMISSION = 'FACADE_MERCHANT_COMMISSION';

    /**
     * @var string
     */
    public const FACADE_MERCHANT_COMMISSION_DATA_EXPORT = 'FACADE_MERCHANT_COMMISSION_DATA_EXPORT';

    /**
     * @var string
     */
    public const FACADE_GLOSSARY = 'FACADE_GLOSSARY';

    /**
     * @var string
     */
    public const FACADE_MONEY = 'FACADE_MONEY';

    /**
     * @var string
     */
    public const SERVICE_UTIL_DATE_TIME = 'SERVICE_UTIL_DATE_TIME';

    /**
     * @var string
     */
    public const SERVICE_UTIL_CSV = 'SERVICE_UTIL_CSV';

    /**
     * @var string
     */
    public const PROPEL_QUERY_MERCHANT_COMMISSION = 'PROPEL_QUERY_MERCHANT_COMMISSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addMerchantCommissionFacade($container);
        $container = $this->addMerchantCommissionDataExportFacade($container);
        $container = $this->addGlossaryFacade($container);
        $container = $this->addMoneyFacade($container);
        $container = $this->addUtilDateTimeService($container);
        $container = $this->addUtilCsvService($container);
        $container = $this->addMerchantCommissionPropelQuery($container);

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
            return new MerchantCommissionGuiToMerchantCommissionFacadeBridge(
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
    protected function addMerchantCommissionDataExportFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_COMMISSION_DATA_EXPORT, function (Container $container) {
            return new MerchantCommissionGuiToMerchantCommissionDataExportFacadeBridge(
                $container->getLocator()->merchantCommissionDataExport()->facade(),
            );
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
            return new MerchantCommissionGuiToGlossaryFacadeBridge($container->getLocator()->glossary()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMoneyFacade(Container $container): Container
    {
        $container->set(static::FACADE_MONEY, function (Container $container) {
            return new MerchantCommissionGuiToMoneyFacadeBridge($container->getLocator()->money()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilDateTimeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_DATE_TIME, function (Container $container) {
            return new MerchantCommissionGuiToUtilDateTimeServiceBridge(
                $container->getLocator()->utilDateTime()->service(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilCsvService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_CSV, function (Container $container) {
            return new MerchantCommissionGuiToUtilCsvServiceBridge($container->getLocator()->utilCsv()->service());
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
}
