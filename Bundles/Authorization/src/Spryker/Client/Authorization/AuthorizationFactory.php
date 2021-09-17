<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Authorization;

use Spryker\Client\Authorization\Authorization\AuthorizationChecker;
use Spryker\Client\Authorization\Authorization\AuthorizationCheckerInterface;
use Spryker\Client\Authorization\Authorization\AuthorizationStrategyCollection;
use Spryker\Client\Authorization\Authorization\AuthorizationStrategyCollectionInterface;
use Spryker\Client\Kernel\AbstractFactory;

class AuthorizationFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Authorization\Authorization\AuthorizationCheckerInterface
     */
    public function createAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return new AuthorizationChecker($this->createAuthorizationStrategyCollection());
    }

    /**
     * @return \Spryker\Client\Authorization\Authorization\AuthorizationStrategyCollectionInterface
     */
    public function createAuthorizationStrategyCollection(): AuthorizationStrategyCollectionInterface
    {
        return new AuthorizationStrategyCollection($this->getAuthorizationStrategyPlugins());
    }

    /**
     * @return array<\Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface>
     */
    public function getAuthorizationStrategyPlugins(): array
    {
        return $this->getProvidedDependency(AuthorizationDependencyProvider::PLUGINS_AUTHORIZATION_STRATEGIES);
    }
}
