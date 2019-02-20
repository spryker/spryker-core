<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\OauthCompanyUser\Communication;

use League\OAuth2\Server\Grant\AbstractGrant;
use Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory;
use Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProvider;
use Spryker\Zed\OauthCompanyUser\Business\CompanyUser\CompanyUserProviderInterface;
use Spryker\Zed\OauthCompanyUser\Business\League\Grant\IdCompanyUserGrantType;
use Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface;
use Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface;
use Spryker\Zed\OauthCompanyUser\OauthCompanyUserDependencyProvider;

/**
 * @method \Spryker\Zed\OauthCompanyUser\Business\OauthCompanyUserFacadeInterface getFacade()
 * @method \Spryker\Zed\OauthCompanyUser\OauthCompanyUserConfig getConfig()
 */
class OauthCompanyUserCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \League\OAuth2\Server\Grant\AbstractGrant
     */
    public function createIdCompanyUserGrantType(): AbstractGrant
    {
        return new IdCompanyUserGrantType($this->createCustomerProvider());
    }

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
     * @return \Spryker\Zed\OauthCompanyUser\Dependency\Facade\OauthCompanyUserToCompanyUserFacadeInterface
     */
    public function getCompanyUserFacade(): OauthCompanyUserToCompanyUserFacadeInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserDependencyProvider::FACADE_COMPANY_USER);
    }

    /**
     * @return \Spryker\Zed\OauthCompanyUser\Dependency\Service\OauthCompanyUserToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): OauthCompanyUserToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(OauthCompanyUserDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
