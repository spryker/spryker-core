<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader\Common;

use Generated\Shared\Transfer\ModuleTransfer;
use ReflectionClass;
use ReflectionMethod;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;

abstract class AbstractMethodChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(ModuleTransfer $moduleTransfer): array
    {
        $className = $this->getClassName($moduleTransfer);

        if (!class_exists($className)) {
            return [];
        }

        $reflectionClass = new ReflectionClass($className);
        $reflectionMethods = $reflectionClass->getMethods();

        $methods = [];

        foreach ($reflectionMethods as $reflectionMethod) {
            if ($this->acceptMethod($reflectionMethod)) {
                $methods[$reflectionMethod->getName()] = $this->buildChoiceLabel($moduleTransfer, $reflectionMethod);
            }
        }

        return $methods;
    }

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return string
     */
    abstract protected function getClassName(ModuleTransfer $moduleTransfer): string;

    /**
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return bool
     */
    abstract protected function acceptMethod(ReflectionMethod $reflectionMethod): bool;

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     * @param \ReflectionMethod $reflectionMethod
     *
     * @return string
     */
    abstract protected function buildChoiceLabel(ModuleTransfer $moduleTransfer, ReflectionMethod $reflectionMethod): string;
}
