<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class ClassNameFilter implements DependencyFilterInterface
{

    /**
     * @var string
     */
    private $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @param array $dependency
     *
     * @return bool
     */
    public function filter(array $dependency)
    {
        $foreignClassName = $dependency[DependencyTree::META_FOREIGN_CLASS_NAME];

        if (preg_match($this->pattern, $foreignClassName)) {
            return true;
        }

        return false;
    }

}
