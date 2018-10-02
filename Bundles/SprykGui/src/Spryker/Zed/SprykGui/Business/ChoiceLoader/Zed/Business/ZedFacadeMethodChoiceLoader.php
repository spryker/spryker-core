<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader\Zed\Business;

use Generated\Shared\Transfer\ModuleTransfer;
use ReflectionClass;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;

class ZedFacadeMethodChoiceLoader implements ChoiceLoaderInterface
{
    /**
     * @var array
     */
    protected $internalMethods = [
        'setFactory',
        'setEntityManager',
        'setRepository',
    ];

    /**
     * @param \Generated\Shared\Transfer\ModuleTransfer $moduleTransfer
     *
     * @return array
     */
    public function loadChoices(ModuleTransfer $moduleTransfer): array
    {
        $dependentModule = $moduleTransfer->getDependentModule();
        $facadeClassName = sprintf('\\Spryker\\Zed\\%1$s\\Business\\%1$sFacade', $dependentModule->getName());

        if (!class_exists($facadeClassName)) {
            return [];
        }

        $reflectionClass = new ReflectionClass($facadeClassName);
        $reflectionMethods = $reflectionClass->getMethods();

        $methods = [];

        foreach ($reflectionMethods as $reflectionMethod) {
            if ($reflectionMethod->isPublic() && !in_array($reflectionMethod->getName(), $this->internalMethods)) {
                $methods[$reflectionMethod->getName()] = sprintf('%sFacade::%s()', $dependentModule->getName(), $reflectionMethod->getName());
            }
        }

        return $methods;
    }
}
