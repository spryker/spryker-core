<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder;

class ViolationFinder implements ViolationFinderInterface
{

    /**
     * @var ViolationFinderInterface[]
     */
    private $violationFinder;

    /**
     * @param ViolationFinderInterface $violationFinder
     *
     * @return self
     */
    public function addViolationFinder(ViolationFinderInterface $violationFinder)
    {
        $this->violationFinder[] = $violationFinder;

        return $this;
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function isViolation(array $dependency)
    {
        $isViolation = false;

        foreach ($this->violationFinder as $violationFinder) {
            if ($violationFinder->isViolation($dependency)) {
                $isViolation = true;
            }
        }

        return $isViolation;
    }

}
