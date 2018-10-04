<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SprykGui\Business\ChoiceLoader\Client;

use Generated\Shared\Transfer\ModuleTransfer;
use ReflectionClass;
use Spryker\Zed\SprykGui\Business\ChoiceLoader\ChoiceLoaderInterface;

class ClientMethodChoiceLoader implements ChoiceLoaderInterface
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
        $clientClassName = sprintf('\\Spryker\\Client\\%1$s\\%1$sClient', $dependentModule->getName());

        if (!class_exists($clientClassName)) {
            return [];
        }

        $reflectionClass = new ReflectionClass($clientClassName);
        $reflectionMethods = $reflectionClass->getMethods();

        $methods = [];

        foreach ($reflectionMethods as $reflectionMethod) {
            if ($reflectionMethod->isPublic() && !in_array($reflectionMethod->getName(), $this->internalMethods)) {
                $methods[$reflectionMethod->getName()] = sprintf('%sClient::%s()', $dependentModule->getName(), $reflectionMethod->getName());
            }
        }

        return $methods;
    }
}
