<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationshipGui;

use Orm\Zed\MerchantRelationship\Persistence\SpyMerchantRelationshipQuery;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToCompanyFacadeBridge;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantFacadeBridge;
use Spryker\Zed\MerchantRelationshipGui\Dependency\Facade\MerchantRelationshipGuiToMerchantRelationshipFacadeBridge;

class MerchantRelationshipGuiDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MERCHANT_RELATIONSHIP = 'FACADE_MERCHANT_RELATIONSHIP';
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';
    public const FACADE_COMPANY = 'FACADE_COMPANY';
    public const PROPEL_MERCHANT_RELATIONSHIP_QUERY = 'PROPEL_MERCHANT_RELATIONSHIP_QUERY';

    public const PLUGINS_MERCHANT_RELATIONSHIP_CREATE_FORM_EXPANDER = 'PLUGINS_MERCHANT_RELATIONSHIP_CREATE_FORM_EXPANDER';
    public const PLUGINS_MERCHANT_RELATIONSHIP_EDIT_FORM_EXPANDER = 'PLUGINS_MERCHANT_RELATIONSHIP_EDIT_FORM_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = parent::provideCommunicationLayerDependencies($container);
        $container = $this->addMerchantRelationshipFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addPropelMerchantRelationshipQuery($container);
        $container = $this->addMerchantRelationshipCreateFormExpanderPlugins($container);
        $container = $this->addMerchantRelationshipEditFormExpanderPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipFacade(Container $container): Container
    {
        $container[static::FACADE_MERCHANT_RELATIONSHIP] = function (Container $container) {
            return new MerchantRelationshipGuiToMerchantRelationshipFacadeBridge($container->getLocator()->merchantRelationship()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY] = function (Container $container) {
            return new MerchantRelationshipGuiToCompanyFacadeBridge($container->getLocator()->company()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_BUSINESS_UNIT] = function (Container $container) {
            return new MerchantRelationshipGuiToCompanyBusinessUnitFacadeBridge($container->getLocator()->companyBusinessUnit()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPropelMerchantRelationshipQuery(Container $container): Container
    {
        $container[static::PROPEL_MERCHANT_RELATIONSHIP_QUERY] = function () {
            return SpyMerchantRelationshipQuery::create();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container[static::FACADE_MERCHANT] = function (Container $container) {
            return new MerchantRelationshipGuiToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipCreateFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_CREATE_FORM_EXPANDER, function () {
            return $this->getMerchantRelationshipCreateFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipEditFormExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATIONSHIP_EDIT_FORM_EXPANDER, function () {
            return $this->getMerchantRelationshipEditFormExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipGuiExtension\Dependency\Plugin\MerchantRelationshipCreateFormExpanderPluginInterface[]
     */
    protected function getMerchantRelationshipCreateFormExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @return \Spryker\Zed\MerchantRelationshipGuiExtension\Dependency\Plugin\MerchantRelationshipEditFormExpanderPluginInterface[]
     */
    protected function getMerchantRelationshipEditFormExpanderPlugins(): array
    {
        return [];
    }
}
