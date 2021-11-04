<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\Authorization\Authorization;

use RuntimeException;
use Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface;

class AuthorizationStrategyCollection implements AuthorizationStrategyCollectionInterface
{
    /**
     * @var array<\Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface>
     */
    protected $authorizationStrategies = [];

    /**
     * @param array<\Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface> $authorizationStrategyPlugins
     */
    public function __construct(array $authorizationStrategyPlugins)
    {
        foreach ($authorizationStrategyPlugins as $authorizationStrategyPlugin) {
            $this->add($authorizationStrategyPlugin->getStrategyName(), $authorizationStrategyPlugin);
        }
    }

    /**
     * @param string $name
     * @param \Spryker\Client\AuthorizationExtension\Dependency\Plugin\AuthorizationStrategyPluginInterface $authorizationStrategy
     *
     * @throws \RuntimeException
     *
     * @return $this
     */
    protected function add(string $name, AuthorizationStrategyPluginInterface $authorizationStrategy)
    {
        if ($this->has($name)) {
            throw new RuntimeException(sprintf(
                'A strategy with the name `%s` is already present in the collection.',
                $name,
            ));
        }

        $this->authorizationStrategies[$name] = $authorizationStrategy;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function has(string $name): bool
    {
        return isset($this->authorizationStrategies[$name]);
    }

    /**
     * @inheritDoc
     *
     * @throws \RuntimeException
     */
    public function get(string $name): AuthorizationStrategyPluginInterface
    {
        if (!$this->has($name)) {
            throw new RuntimeException(sprintf(
                'A strategy with the name `%s` does not exist.',
                $name,
            ));
        }

        return $this->authorizationStrategies[$name];
    }
}
