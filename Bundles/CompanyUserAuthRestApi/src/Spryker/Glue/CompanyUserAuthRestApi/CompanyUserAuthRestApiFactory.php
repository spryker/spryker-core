<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi;

use Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokensReader;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokensReaderInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokenValidator;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokenValidatorInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\RefreshTokens\RefreshTokensReader;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\RefreshTokens\RefreshTokensReaderInterface;
use Spryker\Glue\CompanyUserAuthRestApi\Processor\ResponseFormatter\AuthenticationErrorResponseHeadersFormatter;
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
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\RefreshTokens\RefreshTokensReaderInterface
     */
    public function createRefreshTokensReader(): RefreshTokensReaderInterface
    {
        return new RefreshTokensReader(
            $this->getOauthClient(),
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\AccessTokens\AccessTokenValidatorInterface
     */
    public function createAccessTokenValidator(): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator($this->getOauthClient());
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Processor\ResponseFormatter\AuthenticationErrorResponseHeadersFormatter
     */
    public function createAuthenticationErrorResponseHeadersFormatter(): AuthenticationErrorResponseHeadersFormatter
    {
        return new AuthenticationErrorResponseHeadersFormatter();
    }

    /**
     * @return \Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): AuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(CompanyUserAuthRestApiDependencyProvider::CLIENT_OAUTH);
    }
}
