<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Authorization\Business\Authorization;

use Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;
use Spryker\Zed\Authorization\Business\Exception\AuthorizationStrategyAlreadyPresentException;
use Spryker\Zed\Authorization\Business\Exception\MissingAuthorizationStrategyException;

class AuthorizationStrategyCollection implements AuthorizationStrategyCollectionInterface
{
    /**
     * @var array<\Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface>
     */
    protected $authorizationStrategies = [];

    /**
     * @param array<\Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface> $authorizationStrategyPlugins
     */
    public function __construct(array $authorizationStrategyPlugins)
    {
        foreach ($authorizationStrategyPlugins as $authorizationStrategyPlugin) {
            $this->add($authorizationStrategyPlugin->getStrategyName(), $authorizationStrategyPlugin);
        }
    }

    /**
     * @param string $name
     * @param \Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface $authorizationStrategy
     *
     * @throws \Spryker\Zed\Authorization\Business\Exception\AuthorizationStrategyAlreadyPresentException
     *
     * @return $this
     */
    protected function add(string $name, AuthorizationStrategyPluginInterface $authorizationStrategy)
    {
        if ($this->has($name)) {
            throw new AuthorizationStrategyAlreadyPresentException(sprintf(
                'A strategy with the name `%s` is already present in the collection.',
                $name,
            ));
        }

        $this->authorizationStrategies[$name] = $authorizationStrategy;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->authorizationStrategies[$name]);
    }

    /**
     * @param string $name
     *
     * @throws \Spryker\Zed\Authorization\Business\Exception\MissingAuthorizationStrategyException
     *
     * @return \Spryker\Shared\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface
     */
    public function get(string $name): AuthorizationStrategyPluginInterface
    {
        if (!$this->has($name)) {
            throw new MissingAuthorizationStrategyException(sprintf(
                'A strategy with the name `%s` does not exist.',
                $name,
            ));
        }

        return $this->authorizationStrategies[$name];
    }
}
