<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;

class TravisDependencyFinder extends AbstractFileDependencyFinder
{
    public const TYPE_TRAVIS = 'travis';

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_TRAVIS;
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

        if ($context->getFileInfo()->getFilename() !== '.travis.yml') {
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
        if (preg_match('/code-sniffer\/(Spryker|SprykerStrict)\/ruleset.xml/', $fileContent)) {
            $dependencyContainer->addDependency('CodeSniffer', $this->getType(), false, true);
        }

        if (strpos($fileContent, 'vendor/bin/codecept') !== false) {
            $dependencyContainer->addDependency('Testify', $this->getType(), false, true);
        }

        return $dependencyContainer;
    }
}
