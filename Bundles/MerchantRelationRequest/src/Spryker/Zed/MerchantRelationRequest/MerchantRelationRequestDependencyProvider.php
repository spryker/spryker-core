<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToCompanyUserFacadeBridge;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMailFacadeBridge;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantFacadeBridge;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToMerchantRelationshipFacadeBridge;
use Spryker\Zed\MerchantRelationRequest\Dependency\Facade\MerchantRelationRequestToPermissionFacadeBridge;

/**
 * @method \Spryker\Zed\MerchantRelationRequest\MerchantRelationRequestConfig getConfig()
 */
class MerchantRelationRequestDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_MERCHANT_RELATIONSHIP = 'FACADE_MERCHANT_RELATIONSHIP';

    /**
     * @var string
     */
    public const FACADE_MERCHANT = 'FACADE_MERCHANT';

    /**
     * @var string
     */
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';

    /**
     * @var string
     */
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';

    /**
     * @var string
     */
    public const FACADE_MAIL = 'FACADE_MAIL';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATION_REQUEST_EXPANDER = 'PLUGINS_MERCHANT_RELATION_REQUEST_EXPANDER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATION_REQUEST_POST_CREATE = 'PLUGINS_MERCHANT_RELATION_REQUEST_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_RELATION_REQUEST_POST_UPDATE = 'PLUGINS_MERCHANT_RELATION_REQUEST_POST_UPDATE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);
        $container = $this->addMerchantRelationshipFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addPermissionFacade($container);
        $container = $this->addMerchantFacade($container);
        $container = $this->addMailFacade($container);
        $container = $this->addMerchantRelationRequestExpanderPlugins($container);
        $container = $this->addMerchantRelationRequestPostCreatePlugins($container);
        $container = $this->addMerchantRelationRequestPostUpdatePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationshipFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT_RELATIONSHIP, function (Container $container) {
            return new MerchantRelationRequestToMerchantRelationshipFacadeBridge($container->getLocator()->merchantRelationship()->facade());
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
            return new MerchantRelationRequestToCompanyBusinessUnitFacadeBridge($container->getLocator()->companyBusinessUnit()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantFacade(Container $container): Container
    {
        $container->set(static::FACADE_MERCHANT, function (Container $container) {
            return new MerchantRelationRequestToMerchantFacadeBridge($container->getLocator()->merchant()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container->set(static::FACADE_COMPANY_USER, function (Container $container) {
            return new MerchantRelationRequestToCompanyUserFacadeBridge($container->getLocator()->companyUser()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionFacade(Container $container): Container
    {
        $container->set(static::FACADE_PERMISSION, function (Container $container) {
            return new MerchantRelationRequestToPermissionFacadeBridge($container->getLocator()->permission()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container->set(static::FACADE_MAIL, function (Container $container) {
            return new MerchantRelationRequestToMailFacadeBridge(
                $container->getLocator()->mail()->facade(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationRequestExpanderPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATION_REQUEST_EXPANDER, function () {
            return $this->getMerchantRelationRequestExpanderPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestExpanderPluginInterface>
     */
    protected function getMerchantRelationRequestExpanderPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationRequestPostCreatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATION_REQUEST_POST_CREATE, function () {
            return $this->getMerchantRelationRequestPostCreatePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostCreatePluginInterface>
     */
    protected function getMerchantRelationRequestPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantRelationRequestPostUpdatePlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_RELATION_REQUEST_POST_UPDATE, function () {
            return $this->getMerchantRelationRequestPostUpdatePlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Zed\MerchantRelationRequestExtension\Dependency\Plugin\MerchantRelationRequestPostUpdatePluginInterface>
     */
    protected function getMerchantRelationRequestPostUpdatePlugins(): array
    {
        return [];
    }
}
