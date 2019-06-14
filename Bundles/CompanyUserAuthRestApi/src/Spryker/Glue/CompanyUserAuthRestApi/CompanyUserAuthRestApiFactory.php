<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi;

use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\CompanyUserAccessToken\CompanyUserAccessTokenReader;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\CompanyUserAccessToken\CompanyUserAccessTokenReaderInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUser\RestUserMapper;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUser\RestUserMapperInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig getConfig()
 */
class CompanyUserAuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\CompanyUserAccessToken\CompanyUserAccessTokenReaderInterface
     */
    public function createCompanyUserAccessTokenReader(): CompanyUserAccessTokenReaderInterface
    {
        return new CompanyUserAccessTokenReader(
            $this->getOauthClient(),
            $this->getResourceBuilder()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUser\RestUserMapperInterface
     */
    public function createRestUserMapper(): RestUserMapperInterface
    {
        return new RestUserMapper($this->getCompanyUserStorageClient());
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface
     */
    public function getCompanyUserStorageClient(): CompanyUserAuthRestApiToCompanyUserStorageClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAuthRestApiDependencyProvider::CLIENT_COMPANY_USER_STORAGE);
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): CompanyUserAuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAuthRestApiDependencyProvider::CLIENT_OAUTH);
    }
}
