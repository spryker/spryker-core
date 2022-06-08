<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Authentication;

use Spryker\Client\Authentication\Executor\AuthenticationServerExecutor;
use Spryker\Client\Authentication\Executor\AuthenticationServerExecutorInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AuthenticationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Authentication\Executor\AuthenticationServerExecutorInterface
     */
    public function createAuthenticationServerExecutor(): AuthenticationServerExecutorInterface
    {
        return new AuthenticationServerExecutor(
            $this->getAuthenticationServerPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Shared\AuthenticationExtension\Dependency\Plugin\AuthenticationServerPluginInterface>
     */
    public function getAuthenticationServerPlugins(): array
    {
        return $this->getProvidedDependency(AuthenticationDependencyProvider::PLUGINS_AUTHENTICATION_SERVER);
    }
}
