<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\QuoteApproval;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCartFacadeBridge;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyRoleFacadeBridge;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToCompanyUserFacadeBridge;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToMessengerFacadeBridge;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToPermissionFacadeBridge;
use Spryker\Zed\QuoteApproval\Dependency\Facade\QuoteApprovalToQuoteFacadeBridge;

/**
 * @method \Spryker\Zed\QuoteApproval\QuoteApprovalConfig getConfig()
 */
class QuoteApprovalDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_MESSENGER = 'FACADE_MESSENGER';
    public const FACADE_COMPANY_ROLE = 'FACADE_COMPANY_ROLE';
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_CART = 'FACADE_CART';
    public const FACADE_QUOTE = 'FACADE_QUOTE';
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addCartFacade($container);
        $container = $this->addQuoteFacade($container);
        $container = $this->addCompanyRoleFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addMessengerFacade($container);
        $container = $this->addPermissionFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container)
    {
        $container = $this->addCompanyUserFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCartFacade(Container $container)
    {
        $container[static::FACADE_CART] = function (Container $container) {
            return new QuoteApprovalToCartFacadeBridge($container->getLocator()->cart()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQuoteFacade(Container $container)
    {
        $container[static::FACADE_QUOTE] = function (Container $container) {
            return new QuoteApprovalToQuoteFacadeBridge($container->getLocator()->quote()->facade());
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_USER] = function (Container $container) {
            return new QuoteApprovalToCompanyUserFacadeBridge(
                $container->getLocator()->companyUser()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyRoleFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_ROLE] = function (Container $container) {
            return new QuoteApprovalToCompanyRoleFacadeBridge(
                $container->getLocator()->companyRole()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMessengerFacade(Container $container): Container
    {
        $container[static::FACADE_MESSENGER] = function (Container $container) {
            return new QuoteApprovalToMessengerFacadeBridge(
                $container->getLocator()->messenger()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionFacade(Container $container): Container
    {
        $container[static::FACADE_PERMISSION] = function (Container $container) {
            return new QuoteApprovalToPermissionFacadeBridge(
                $container->getLocator()->permission()->facade()
            );
        };

        return $container;
    }
}
