<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authorization\Business;

use Spryker\Zed\Authorization\AuthorizationDependencyProvider;
use Spryker\Zed\Authorization\Business\Authorization\AuthorizationChecker;
use Spryker\Zed\Authorization\Business\Authorization\AuthorizationCheckerInterface;
use Spryker\Zed\Authorization\Business\Authorization\AuthorizationStrategyCollection;
use Spryker\Zed\Authorization\Business\Authorization\AuthorizationStrategyCollectionInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Authorization\AuthorizationConfig getConfig()
 */
class AuthorizationBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Authorization\Business\Authorization\AuthorizationCheckerInterface
     */
    public function createAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return new AuthorizationChecker($this->createAuthorizationStrategyCollection());
    }

    /**
     * @return \Spryker\Zed\Authorization\Business\Authorization\AuthorizationStrategyCollectionInterface
     */
    public function createAuthorizationStrategyCollection(): AuthorizationStrategyCollectionInterface
    {
        return new AuthorizationStrategyCollection($this->getAuthorizationStrategyPlugins());
    }

    /**
     * @return array<\Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface>
     */
    public function getAuthorizationStrategyPlugins(): array
    {
        return $this->getProvidedDependency(AuthorizationDependencyProvider::PLUGINS_AUTHORIZATION_STRATEGIES);
    }
}
