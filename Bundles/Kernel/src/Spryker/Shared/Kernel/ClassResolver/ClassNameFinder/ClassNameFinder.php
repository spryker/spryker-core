<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Kernel\ClassResolver\ClassNameFinder;

use Spryker\Shared\Kernel\ClassResolver\ClassNameCandidatesBuilder\ClassNameCandidatesBuilderInterface;
use Throwable;

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
     * @param string $moduleName
     * @param string $classNamePattern
     * @param bool $throwException
     *
     * @return string|null
     */
    public function findClassName(string $moduleName, string $classNamePattern, bool $throwException = true): ?string
    {
        $classNameCandidates = $this->classNameCandidatesBuilder->buildClassNames($moduleName, $classNamePattern);

        foreach ($classNameCandidates as $classNameCandidate) {
            $className = $this->tryClassName($classNameCandidate, $throwException);
            if ($className !== null) {
                return $className;
            }
        }

        return null;
    }

    /**
     * @param string $classNameCandidate
     * @param bool $throwException
     *
     * @throws \Throwable
     *
     * @return string|null
     */
    protected function tryClassName(string $classNameCandidate, bool $throwException): ?string
    {
        try {
            if (class_exists($classNameCandidate)) {
                return $classNameCandidate;
            }
        } catch (Throwable $throwable) {
            if ($throwException) {
                throw $throwable;
            }
        }

        return null;
    }
}
