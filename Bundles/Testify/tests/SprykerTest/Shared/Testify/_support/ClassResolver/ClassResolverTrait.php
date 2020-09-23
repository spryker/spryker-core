<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Shared\Testify\ClassResolver;

use Codeception\Configuration;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

trait ClassResolverTrait
{
    use LocatorHelperTrait;

    /**
     * @var string[]
     */
    protected $coreNamespaces = [
        'Spryker',
        'SprykerShop',
    ];

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return object|null
     */
    protected function resolveClass(string $classNamePattern, string $moduleName): ?object
    {
        $resolvedClassName = $this->resolveClassName($classNamePattern, $moduleName);

        if ($resolvedClassName === null) {
            return null;
        }

        return new $resolvedClassName();
    }

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return string|null
     */
    protected function resolveClassName(string $classNamePattern, string $moduleName): ?string
    {
        $classNameCandidates = $this->getClassNameCandidates($classNamePattern, $moduleName);

        foreach ($classNameCandidates as $classNameCandidate) {
            if (class_exists($classNameCandidate)) {
                return $classNameCandidate;
            }
        }

        return null;
    }

    /**
     * @param string $classNamePattern
     * @param string $moduleName
     *
     * @return string[]
     */
    protected function getClassNameCandidates(string $classNamePattern, string $moduleName): array
    {
        $config = Configuration::config();
        $namespaceParts = explode('\\', $config['namespace']);

        $classNameCandidates = [];
        $classNameCandidates[] = sprintf($classNamePattern, rtrim($namespaceParts[0], 'Test'), $namespaceParts[1], $moduleName);

        foreach ($this->coreNamespaces as $coreNamespace) {
            $classNameCandidates[] = sprintf($classNamePattern, $coreNamespace, $namespaceParts[1], $moduleName);
        }

        return $classNameCandidates;
    }
}
