<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUserConnector\Business;

use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\OauthCompanyUserConnector\Business\CompanyUser\CompanyUserProvider;
use Spryker\Zed\OauthCompanyUserConnector\Business\CompanyUser\CompanyUserProviderInterface;
use Spryker\Zed\OauthCompanyUserConnector\Business\Scope\ScopeProvider;
use Spryker\Zed\OauthCompanyUserConnector\Business\Scope\ScopeProviderInterface;
use Spryker\Zed\OauthCompanyUserConnector\Dependency\Facade\OauthCompanyUserConnectorToCompanyUserFacadeInterface;
use Spryker\Zed\OauthCompanyUserConnector\Dependency\Service\OauthCompanyUserConnectorToUtilEncodingServiceInterface;
use Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorDependencyProvider;

/**
 * @method \Spryker\Zed\OauthCompanyUserConnector\OauthCompanyUserConnectorConfig getConfig()
 */
class OauthCompanyUserConnectorBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\OauthCompanyUserConnector\Business\CompanyUser\CompanyUserProviderInterface
     */
    public function createCustomerProvider(): CompanyUserProviderInterface
    {
        return new CompanyUserProvider(
            $this->getCompanyUserFacade(),
            $this->getUtilEncodingService()
        );
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUserConnector\Business\Scope\ScopeProviderInterface
     */
    public function createScopeProvider(): ScopeProviderInterface
    {
        return new ScopeProvider($this->getConfig());
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUserConnector\Dependency\Facade\OauthCompanyUserConnectorToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): OauthCompanyUserConnectorToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserConnectorDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUserConnector\Dependency\Service\OauthCompanyUserConnectorToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthCompanyUserConnectorToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserConnectorDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
