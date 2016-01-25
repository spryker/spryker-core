<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationChecker;

interface DependencyViolationCheckerInterface
{

    /**
     * @return array
     */
    public function getDependencyViolations();

}
