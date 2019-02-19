<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi;

use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthCompanyUserClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessToken\AccessTokenReader;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessToken\AccessTokenReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig getConfig()
 */
class CompanyUserAuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessToken\AccessTokenReaderInterface
     */
    public function createAccessTokenReader(): AccessTokenReaderInterface
    {
        return new AccessTokenReader(
            $this->getOauthClient(),
            $this->getOauthCompanyUserClient(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): CompanyUserAuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAuthRestApiDependencyProvider::CLIENT_OAUTH);
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthCompanyUserClientInterface
     */
    public function getOauthCompanyUserClient(): CompanyUserAuthRestApiToOauthCompanyUserClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAuthRestApiDependencyProvider::CLIENT_OAUTH_COMPANY_USER);
    }
}
