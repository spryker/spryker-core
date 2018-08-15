<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\Dependency\DependencyFinder;

use Spryker\Zed\Development\Business\Dependency\DependencyContainer\DependencyContainerInterface;
use Spryker\Zed\Development\Business\Dependency\DependencyFinder\Context\DependencyFinderContextInterface;

class TwigDependencyFinder implements DependencyFinderInterface
{
    public const TYPE_TWIG = 'twig';

    /**
     * @var \Spryker\Zed\Development\Business\Dependency\DependencyFinder\TwigDependencyFinder\TwigDependencyFinderInterface[]
     */
    protected $twigDependencyFinder;

    /**
     * @param array $twigDependencyFinder
     */
    public function __construct(array $twigDependencyFinder)
    {
        $this->twigDependencyFinder = $twigDependencyFinder;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return static::TYPE_TWIG;
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

        if ($context->getFileInfo()->getExtension() !== 'twig') {
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
        foreach ($this->twigDependencyFinder as $twigDependencyFinder) {
            $dependencyContainer = $twigDependencyFinder->checkDependencyInFile($context, $dependencyContainer);
        }

        return $dependencyContainer;
    }
}
