<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ClassNameFinder;

use Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderInterface;

class ClassNameFinder implements ClassNameFinderInterface
{
    /**
     * @var \Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderInterface
     */
    protected $classNameCandidatesBuilder;

    /**
     * @param \Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderInterface $classNameCandidatesBuilder
     */
    public function __construct(ClassNameCandidatesBuilderInterface $classNameCandidatesBuilder)
    {
        $this->classNameCandidatesBuilder = $classNameCandidatesBuilder;
    }

    /**
     * @param string $module
     * @param string $classNamePattern
     *
     * @return string|null
     */
    public function findClassName(string $module, string $classNamePattern): ?string
    {
        $classNameCandidates = $this->classNameCandidatesBuilder->buildClassNames($module, $classNamePattern);

        foreach ($classNameCandidates as $classNameCandidate) {
            if (class_exists($classNameCandidate)) {
                return $classNameCandidate;
            }
        }

        return null;
    }
}
