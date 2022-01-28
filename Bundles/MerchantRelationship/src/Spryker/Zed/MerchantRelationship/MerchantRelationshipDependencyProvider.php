<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationship;

use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\MerchantRelationship\Dependency\Facade\MerchantRelationshipToMerchantFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantRelationship\MerchantRelationshipConfig getConfig()
 */
class MerchantRelationshipDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATIONSHIP_PRE_DELETE = 'PLUGINS_MERCHANT_RELATIONSHIP_PRE_DELETE';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATIONSHIP_POST_UPDATE = 'PLUGINS_MERCHANT_RELATIONSHIP_POST_UPDATE';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATIONSHIP_POST_CREATE = 'PLUGINS_MERCHANT_RELATIONSHIP_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATIONSHIP_CREATE_VALIDATOR = 'PLUGINS_MERCHANT_RELATIONSHIP_CREATE_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATIONSHIP_UPDATE_VALIDATOR = 'PLUGINS_MERCHANT_RELATIONSHIP_UPDATE_VALIDATOR';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATIONSHIP_EXPANDER = 'PLUGINS_MERCHANT_RELATIONSHIP_EXPANDER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COMPANY_BUSINESS_UNIT = 'PROPEL_QUERY_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMerchantRelationshipPreDeletePlugins($container);
        $container = $this->addMerchantRelationshipPostCreatePlugins($container);
        $container = $this->addMerchantRelationshipPostUpdatePlugins($container);
        $container = $this->addMerchantRelationshipCreateValidatorPlugins($container);
        $container = $this->addMerchantRelationshipUpdateValidatorPlugins($container);
        $container = $this->addMerchantRelationshipExpanderPlugins($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);

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
        $container = $this->addCompanyBusinessUnitPropelQuery($container);

        return $container;
    }

    /**
     * @module CompanyBusinessUnit
     *
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCompanyBusinessUnitPropelQuery(Container $container): Container
    {
        $container->set(static::PROPEL_QUERY_COMPANY_BUSINESS_UNIT, $container->factory(function () {
            return SpyCompanyBusinessUnitQuery::create();
        }));

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPreDeletePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_PRE_DELETE, function () {
            return $this->getMerchantRelationshipPreDeletePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_POST_CREATE, function () {
            return $this->getMerchantRelationshipPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_POST_UPDATE, function () {
            return $this->getMerchantRelationshipPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipCreateValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_CREATE_VALIDATOR, function () {
            return $this->getMerchantRelationshipCreateValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipUpdateValidatorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_UPDATE_VALIDATOR, function () {
            return $this->getMerchantRelationshipUpdateValidatorPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_EXPANDER, function () {
            return $this->getMerchantRelationshipExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPreDeletePluginInterface>
     */
    protected function getMerchantRelationshipPreDeletePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostCreatePluginInterface>
     */
    protected function getMerchantRelationshipPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipPostUpdatePluginInterface>
     */
    protected function getMerchantRelationshipPostUpdatePlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipCreateValidatorPluginInterface>
     */
    protected function getMerchantRelationshipCreateValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipUpdateValidatorPluginInterface>
     */
    protected function getMerchantRelationshipUpdateValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\MerchantRelationshipExtension\Dependency\Plugin\MerchantRelationshipExpanderPluginInterface>
     */
    protected function getMerchantRelationshipExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantRelationshipToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_BUSINESS_UNIT, function (Container $container) {
            return new MerchantRelationshipToCompanyBusinessUnitFacadeBridge($container->getLocator()->companyBusinessUnit()->facade());
        });

        return $container;
    }
}
