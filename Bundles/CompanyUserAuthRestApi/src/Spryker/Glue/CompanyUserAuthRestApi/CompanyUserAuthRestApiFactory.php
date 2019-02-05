<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi;

use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokensReader;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokensReaderInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUserIdentifier\RestUserIdentifierExpander;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUserIdentifier\RestUserIdentifierExpanderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\CompanyUserAuthRestApi\CompanyUserAuthRestApiConfig getConfig()
 */
class CompanyUserAuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokensReaderInterface
     */
    public function createAccessTokensReader(): AccessTokensReaderInterface
    {
        return new AccessTokensReader(
            $this->getOauthClient(),
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\RestUserIdentifier\RestUserIdentifierExpanderInterface
     */
    public function createRestUserIdentifierExpander(): RestUserIdentifierExpanderInterface
    {
        return new RestUserIdentifierExpander($this->getCompanyUserStorageClient());
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): CompanyUserAuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAuthRestApiDependencyProvider::CLIENT_OAUTH);
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\CompanyUserAuthRestApiToCompanyUserStorageClientInterface
     */
    public function getCompanyUserStorageClient(): CompanyUserAuthRestApiToCompanyUserStorageClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAuthRestApiDependencyProvider::CLIENT_COMPANY_USER_STORAGE);
    }
}
