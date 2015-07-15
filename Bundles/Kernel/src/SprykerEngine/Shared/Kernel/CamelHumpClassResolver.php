<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerEngine\Shared\Kernel;

use SprykerEngine\Shared\Kernel\ClassResolver\ClassNameAmbiguousException;
use SprykerEngine\Shared\Kernel\ClassResolver\ClassNotFoundException;
use SprykerEngine\Shared\Kernel\ClassResolver\ClassResolverInterface;

class CamelHumpClassResolver implements ClassResolverInterface
{

    /**
     * @var ClassResolverInterface
     */
    private $classResolver;

    /**
     * @param ClassResolverInterface $classResolver
     */
    public function __construct(ClassResolverInterface $classResolver)
    {
        $this->classResolver = $classResolver;
    }

    /**
     * @param string $classNamePattern
     * @param string $bundle
     *
     * @throws ClassNameAmbiguousException
     *
     * @return bool
     */
    public function canResolve($classNamePattern, $bundle)
    {
        $classBaseName = $this->getClassBaseName($classNamePattern);
        $camelHumpClassParts = $this->getCamelHumpClassParts($classNamePattern);

        $combinations = $this->findCombinations($camelHumpClassParts);

        $canResolve = false;

        foreach ($combinations as $className) {
            $classNameToResolve = $classBaseName . $className;

            if ($this->classResolver->canResolve($classNameToResolve, $bundle)) {
                if ($canResolve === true) {
                    throw new ClassNameAmbiguousException();
                } else {
                    $canResolve = true;
                }
            }
        }

        return $canResolve;
    }

    /**
     * @param string $classNamePattern
     * @param string $bundle
     * @param array $arguments
     *
     * @throws \Exception
     *
     * @return object
     */
    public function resolve($classNamePattern, $bundle, array $arguments = [])
    {
        $classBaseName = $this->getClassBaseName($classNamePattern);
        $camelHumpClassParts = $this->getCamelHumpClassParts($classNamePattern);
        $combinations = $this->findCombinations($camelHumpClassParts);

        $resolvedClass = null;

        foreach ($combinations as $className) {
            $classNameToResolve = $classBaseName . $className;

            if ($this->classResolver->canResolve($classNameToResolve, $bundle)) {
                if (!is_null($resolvedClass)) {
                    throw new ClassNameAmbiguousException();
                } else {
                    $resolvedClass = $this->classResolver->resolve($classNameToResolve, $bundle, $arguments);
                }
            }
        }

        if (!is_null($resolvedClass)) {
            return $resolvedClass;
        } else {
            throw new ClassNotFoundException(sprintf('Could not find "%s"', $classNamePattern));
        }
    }

    /**
     * @param array $parts
     * @param int $length
     *
     * @return array
     */
    private function findCombinations(array $parts, $length = 0)
    {
        if (!$length) {
            $length = count($parts);
        }

        if ($length === 1) {
            return [$parts[0]];
        }

        $part = $parts[$length - 1];
        $recursionResult = $this->findCombinations($parts, $length - 1);
        $functionResult = [];
        foreach ($recursionResult as $rr) {
            $functionResult[] = $rr . '\\' . $part;
            $functionResult[] = $rr . $part;
        }

        return $functionResult;
    }

    /**
     * @param string $classNamePattern
     *
     * @return array
     */
    private function getCamelHumpClassParts($classNamePattern)
    {
        $classNameParts = (array) explode('\\', $classNamePattern);
        $className = array_pop($classNameParts);

        return preg_split('/(?<=[a-z])(?![a-z])/', $className, -1, PREG_SPLIT_NO_EMPTY);
    }

    /**
     * @param string $classNamePattern
     *
     * @return array
     */
    private function getClassBaseName($classNamePattern)
    {
        $classNameParts = (array) explode('\\', $classNamePattern);
        $className = array_pop($classNameParts);

        return implode('\\', $classNameParts) . '\\';
    }

}
