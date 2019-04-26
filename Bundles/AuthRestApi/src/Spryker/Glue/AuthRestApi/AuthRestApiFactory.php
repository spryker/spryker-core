<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi;

use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToOauthServiceInterface;
use Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToUtilEncodingServiceInterface;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReader;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReaderInterface;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenUserFinder;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenUserFinderInterface;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenValidator;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenValidatorInterface;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthAccessTokenValidator;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthAccessTokenValidatorInterface;
use Spryker\Glue\AuthRestApi\Processor\RefreshTokens\RefreshTokensReader;
use Spryker\Glue\AuthRestApi\Processor\RefreshTokens\RefreshTokensReaderInterface;
use Spryker\Glue\AuthRestApi\Processor\ResponseFormatter\AuthenticationErrorResponseHeadersFormatter;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiConfig getConfig()
 */
class AuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReaderInterface
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
     * @return \Spryker\Glue\AuthRestApi\Processor\RefreshTokens\RefreshTokensReaderInterface
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
     * @deprecated Use createOauthAccessTokenValidator() instead.
     *
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenValidatorInterface
     */
    public function createAccessTokenValidator(): AccessTokenValidatorInterface
    {
        return new AccessTokenValidator($this->getOauthClient());
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Processor\ResponseFormatter\AuthenticationErrorResponseHeadersFormatter
     */
    public function createAuthenticationErrorResponseHeadersFormatter(): AuthenticationErrorResponseHeadersFormatter
    {
        return new AuthenticationErrorResponseHeadersFormatter();
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokenUserFinderInterface
     */
    public function createAccessTokenUserFinder(): AccessTokenUserFinderInterface
    {
        return new AccessTokenUserFinder(
            $this->getOauthService(),
            $this->getUtilEncodingService(),
            $this->getRestUserExpanderPlugins()
        );
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\OauthAccessTokenValidatorInterface
     */
    public function createOauthAccessTokenValidator(): OauthAccessTokenValidatorInterface
    {
        return new OauthAccessTokenValidator($this->getOauthClient());
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): AuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::CLIENT_OAUTH);
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToOauthServiceInterface
     */
    public function getOauthService(): AuthRestApiToOauthServiceInterface
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::SERVICE_OAUTH);
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Dependency\Service\AuthRestApiToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): AuthRestApiToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Spryker\Glue\AuthRestApiExtension\Dependency\Plugin\RestUserMapperPluginInterface[]
     */
    public function getRestUserExpanderPlugins(): array
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::PLUGINS_REST_USER_EXPANDER);
    }
}
