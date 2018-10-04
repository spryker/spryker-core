<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader\Service;

use Generated\Shared\Transfer\ModuleTransfer;
use ReflectionClass;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;

class ServiceMethodChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * @var array
     */
    protected $internalMethods = [];

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(ModuleTransfer $moduleTransfer): array
    {
        $dependentModule = $moduleTransfer->getDependentModule();
        $serviceClassName = sprintf('\\Spryker\\Service\\%1$s\\%1$sService', $dependentModule->getName());

        if (!class_exists($serviceClassName)) {
            return [];
        }

        $reflectionClass = new ReflectionClass($serviceClassName);
        $reflectionMethods = $reflectionClass->getMethods();

        $methods = [];

        foreach ($reflectionMethods as $reflectionMethod) {
            if ($reflectionMethod->isPublic() && !in_array($reflectionMethod->getName(), $this->internalMethods)) {
                $methods[$reflectionMethod->getName()] = sprintf('%sService::%s()', $dependentModule->getName(), $reflectionMethod->getName());
            }
        }

        return $methods;
    }
}
