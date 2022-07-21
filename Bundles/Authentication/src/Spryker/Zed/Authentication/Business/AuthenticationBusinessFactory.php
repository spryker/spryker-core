<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authentication\Business;

use Spryker\Zed\Authentication\AuthenticationDependencyProvider;
use Spryker\Zed\Authentication\Business\Executor\AuthenticationServerExecutor;
use Spryker\Zed\Authentication\Business\Executor\AuthenticationServerExecutorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Authentication\AuthenticationConfig getConfig()
 */
class AuthenticationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Authentication\Business\Executor\AuthenticationServerExecutorInterface
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
