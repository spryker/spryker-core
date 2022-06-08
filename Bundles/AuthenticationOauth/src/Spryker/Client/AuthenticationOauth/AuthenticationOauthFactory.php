<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\AuthenticationOauth;

use Spryker\Client\AuthenticationOauth\Dependency\Client\AuthenticationOauthToZedRequestClientInterface;
use Spryker\Client\AuthenticationOauth\Stub\AuthenticationOauthStub;
use Spryker\Client\AuthenticationOauth\Stub\AuthenticationOauthStubInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AuthenticationOauthFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\AuthenticationOauth\Stub\AuthenticationOauthStubInterface
     */
    public function createAuthenticationOauthStub(): AuthenticationOauthStubInterface
    {
        return new AuthenticationOauthStub(
            $this->getZedRequestClient(),
        );
    }

    /**
     * @return \Spryker\Client\AuthenticationOauth\Dependency\Client\AuthenticationOauthToZedRequestClientInterface
     */
    public function getZedRequestClient(): AuthenticationOauthToZedRequestClientInterface
    {
        return $this->getProvidedDependency(AuthenticationOauthDependencyProvider::CLIENT_ZED_REQUEST);
    }
}
