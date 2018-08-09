<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder;
use Symfony\Component\Finder\SplFileInfo;

class AtomFunctionDependencyFinder implements TwigDependencyFinderInterface
{
    protected const TEMPLATE_FUNCTION_PATTERN = '/atom\(\'(.*?),\s\'(.*?)\'/';

    /**
     * @param string $module
     * @param \Symfony\Component\Finder\SplFileInfo $twigFileInfo
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    public function checkDependencyInFile(string $module, SplFileInfo $twigFileInfo, DependencyContainerInterface $dependencyContainer): DependencyContainerInterface
    {
        $twigFileContent = $twigFileInfo->getContents();

        $pregMatchResult = preg_match_all(static::TEMPLATE_FUNCTION_PATTERN, $twigFileContent, $matches, PREG_SET_ORDER);

        if ($pregMatchResult === false || $pregMatchResult === 0) {
            return $dependencyContainer;
        }

        return $this->addFindings($module, $matches, $dependencyContainer);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return TwigDependencyFinder::TYPE_TWIG;
    }

    /**
     * @param string $module
     * @param array $matches
     * @param \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface $dependencyContainer
     *
     * @return \Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface
     */
    protected function addFindings(string $module, array $matches, DependencyContainerInterface $dependencyContainer): DependencyContainerInterface
    {
        foreach ($matches as $match) {
            $foundModule = $match[2];
            if ($foundModule === $module) {
                continue;
            }
            $dependencyContainer->addDependency($foundModule, $this->getType(), true);
        }

        return $dependencyContainer;
    }
}
