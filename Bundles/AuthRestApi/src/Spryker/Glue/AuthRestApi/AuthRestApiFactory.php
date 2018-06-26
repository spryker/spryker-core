<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi;

use Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReader;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReaderInterface;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\ValidateAccessToken;
use Spryker\Glue\AuthRestApi\Processor\AccessTokens\ValidateAccessTokenInterface;
use Spryker\Glue\AuthRestApi\Processor\RefreshTokens\RefreshTokensReader;
use Spryker\Glue\AuthRestApi\Processor\RefreshTokens\RefreshTokensReaderInterface;
use Spryker\Glue\Kernel\AbstractFactory;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiConfig getConfig()
 */
class AuthRestApiFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\AccessTokensReaderInterface
     */
    public function createAccessTokenReader(): AccessTokensReaderInterface
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
    public function createRefreshTokenReader(): RefreshTokensReaderInterface
    {
        return new RefreshTokensReader(
            $this->getOauthClient(),
            $this->getResourceBuilder(),
            $this->getConfig()
        );
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Processor\AccessTokens\ValidateAccessTokenInterface
     */
    public function createValidateAccessToken(): ValidateAccessTokenInterface
    {
        return new ValidateAccessToken($this->getOauthClient());
    }

    /**
     * @return \Spryker\Glue\AuthRestApi\Dependency\Client\AuthRestApiToOauthClientInterface
     */
    public function getOauthClient(): AuthRestApiToOauthClientInterface
    {
        return $this->getProvidedDependency(AuthRestApiDependencyProvider::CLIENT_OAUTH);
    }
}
