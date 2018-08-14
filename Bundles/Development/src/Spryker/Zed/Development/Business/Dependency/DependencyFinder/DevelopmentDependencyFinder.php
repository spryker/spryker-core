<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\DevelopmentConfig;

class DevelopmentDependencyFinder extends AbstractFileDependencyFinder
{
    public const TYPE_DEVELOPMENT = 'development';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $developmentConfig;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $developmentConfig
     */
    public function __construct(DevelopmentConfig $developmentConfig)
    {
        $this->developmentConfig = $developmentConfig;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_DEVELOPMENT;
    }

    /**
     * @param string $module
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     * @param string|null $dependencyType
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function findDependencies(string $module, DependencyContainerInterface $dependencyContainer, ?string $dependencyType = null): DependencyContainerInterface
    {
        if ($dependencyType !== null && $dependencyType !== $this->getType()) {
            return $dependencyContainer;
        }

        foreach ($this->developmentConfig->getDevelopmentModules() as $developmentModule) {
            $dependencyContainer->addDependency(
                $developmentModule,
                $this->getType(),
                false,
                true
            );
        }

        return $dependencyContainer;
    }
}
