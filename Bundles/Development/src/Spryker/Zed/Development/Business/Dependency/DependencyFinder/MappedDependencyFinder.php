<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;
use Spryker\Zed\Development\DevelopmentConfig;

class MappedDependencyFinder extends AbstractFileDependencyFinder
{
    public const TYPE_MAPPED = 'mapped';

    /**
     * @var \Spryker\Zed\Development\DevelopmentConfig
     */
    protected $config;

    /**
     * @param \Spryker\Zed\Development\DevelopmentConfig $config
     */
    public function __construct(DevelopmentConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_MAPPED;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface $context
     *
     * @return bool
     */
    public function accept(DependencyFinderContextInterface $context): bool
    {
        if ($context->getDependencyType() !== null && $context->getDependencyType() !== $this->getType()) {
            return false;
        }

        if ($context->getFileInfo()->getFilename() !== 'composer.json') {
            return false;
        }

        return true;
    }

    /**
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface $context
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function findDependencies(DependencyFinderContextInterface $context, DependencyContainerInterface $dependencyContainer): DependencyContainerInterface
    {
        $fileContent = $context->getFileInfo()->getContents();
        $composerJsonAsArray = json_decode($fileContent, true);
        $composerName = $composerJsonAsArray['name'];

        foreach ($this->config->getExternalToInternalMap() as $externalPackageName => $internalModulePackageName) {
            if ($internalModulePackageName === $composerName) {
                $dependencyContainer->addDependency($externalPackageName, $this->getType());
            }
        }

        return $dependencyContainer;
    }
}
