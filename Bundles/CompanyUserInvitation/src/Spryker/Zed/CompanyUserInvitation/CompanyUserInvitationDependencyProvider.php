<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserInvitation;

use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyBusinessUnitFacadeBridge;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToCompanyUserFacadeBridge;
use Spryker\Zed\CompanyUserInvitation\Dependency\Facade\CompanyUserInvitationToMailFacadeBridge;
use Spryker\Zed\CompanyUserInvitation\Dependency\Service\CompanyUserInvitationToUtilTextBridge;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

class CompanyUserInvitationDependencyProvider extends AbstractBundleDependencyProvider
{
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';
    public const FACADE_MAIL = 'FACADE_COMPANY_USER_INVITATION_TO_MAIL_FACADE_BRIDGE';
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addMailFacade($container);
        $container = $this->addUtilTextService($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container): Container
    {
        $container = $this->addCompanyUserFacade($container);

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
            return new CompanyUserInvitationToCompanyUserFacadeBridge(
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
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_BUSINESS_UNIT] = function (Container $container) {
            return new CompanyUserInvitationToCompanyBusinessUnitFacadeBridge(
                $container->getLocator()->companyBusinessUnit()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMailFacade(Container $container): Container
    {
        $container[static::FACADE_MAIL] = function (Container $container) {
            return new CompanyUserInvitationToMailFacadeBridge(
                $container->getLocator()->mail()->facade()
            );
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_TEXT] = function (Container $container) {
            return new CompanyUserInvitationToUtilTextBridge(
                $container->getLocator()->utilText()->service()
            );
        };

        return $container;
    }
}
