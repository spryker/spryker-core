<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProvider;
use Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProviderInterface;
use Spryker\Zed\OauthCompanyUser\Business\Installer\OauthScopeInstaller;
use Spryker\Zed\OauthCompanyUser\Business\Installer\OauthScopeInstallerInterface;
use Spryker\Zed\OauthCompanyUser\Business\Scope\ScopeProvider;
use Spryker\Zed\OauthCompanyUser\Business\Scope\ScopeProviderInterface;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface;
use Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface;
use Spryker\Zed\OauthCompanyUser\OauthCompanyUserDependencyProvider;

/**
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class OauthCompanyUserBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProviderInterface
     */
    public function createCustomerProvider(): CompanyUserProviderInterface
    {
        return new CompanyUserProvider(
            $this->getCompanyUserFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUser\Business\Scope\ScopeProviderInterface
     */
    public function createScopeProvider(): ScopeProviderInterface
    {
        return new ScopeProvider($this->getConfig());
    }

    public function createOauthScopeInstaller(): OauthScopeInstallerInterface
    {
        return new OauthScopeInstaller(
            $this->getOauthFacade(),
            $this->getConfig()
        );
    }
    /**
     * @return \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): OauthCompanyUserToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToOauthFacadeInterface
     */
    public function getOauthFacade(): OauthCompanyUserToOauthFacadeInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserDependencyProvider::FACADE_OAUTH);
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthCompanyUserToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
