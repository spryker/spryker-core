<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\ViolationChecker;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\AbstractDependencyTreeReader;

class DependencyViolationChecker implements DependencyViolationCheckerInterface
{

    /**
     * @var AbstractDependencyTreeReader
     */
    private $treeReader;

    /**
     * @var array
     */
    private $dependencyViolations = [];

    /**
     * @param AbstractDependencyTreeReader $treeReader
     */
    public function __construct(AbstractDependencyTreeReader $treeReader)
    {
        $this->treeReader = $treeReader;
    }

    /**
     * @return array
     */
    public function getDependencyViolations()
    {
        $dependencyTree = $this->treeReader->read();

        foreach ($dependencyTree as $foreignBundle) {
            foreach ($foreignBundle as $dependencies) {
                foreach ($dependencies as $dependency) {
                    $this->validateDependency($dependency);
                }
            }
        }

        return $this->dependencyViolations;
    }

    /**
     * @param array $dependency
     *
     * @return void
     */
    private function validateDependency(array $dependency)
    {
        $violationPatterns = [
            '/Exception/',
            '/Spryker\\\\Shared\\\\(.*?)Constants/',
        ];

        foreach ($violationPatterns as $violationPattern) {
            if (preg_match($violationPattern, $dependency[DependencyTree::META_FOREIGN_CLASS_NAME])) {
                $this->dependencyViolations[] = $dependency[DependencyTree::META_BUNDLE] . ' \ ' . $dependency[DependencyTree::META_FILE] . ' => ' . $dependency[DependencyTree::META_FOREIGN_CLASS_NAME];
            }
        }
    }
}
