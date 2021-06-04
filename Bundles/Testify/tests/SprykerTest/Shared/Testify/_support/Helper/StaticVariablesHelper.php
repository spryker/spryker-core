<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\Helper;

use ReflectionClass;

trait StaticVariablesHelper
{
    /**
     * @var array
     */
    protected $staticDefinitions = [];

    /**
     * @param string $className
     * @param string $propertyName
     * @param mixed|null $value
     *
     * @return void
     */
    protected function cleanupStaticCache(string $className, string $propertyName, $value = null): void
    {
        $classReflection = new ReflectionClass($className);

        if (!$classReflection->hasProperty($propertyName)) {
            return;
        }

        $propertyReflection = $classReflection->getProperty($propertyName);
        $propertyReflection->setAccessible(true);

        $this->staticDefinitions[] = [
            'className' => $className,
            'propertyName' => $propertyName,
            'value' => $propertyReflection->getValue(),
        ];

        $propertyReflection->setValue($value);
    }

    /**
     * @return void
     */
    protected function resetStaticCaches(): void
    {
        foreach ($this->staticDefinitions as $staticDefinition) {
            $classReflection = new ReflectionClass($staticDefinition['className']);
            $propertyReflection = $classReflection->getProperty($staticDefinition['propertyName']);
            $propertyReflection->setAccessible(true);
            $propertyReflection->setValue($staticDefinition['value']);
        }
    }
}
