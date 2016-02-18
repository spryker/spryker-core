<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder;

class ViolationFinder implements ViolationFinderInterface
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\ViolationFinderInterface[]
     */
    private $violationFinder;

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\ViolationFinder\ViolationFinderInterface $violationFinder
     *
     * @return $this
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
