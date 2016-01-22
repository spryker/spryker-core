<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class ClassNameFilter extends AbstractDependencyFilter
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
     * @param string $bundle
     * @param array $context
     *
     * @return bool
     */
    public function filter($bundle, array $context)
    {
        $foreignClassName = $context[DependencyTree::META_FOREIGN_CLASS_NAME];

        if (preg_match($this->pattern, $foreignClassName)) {
            true;
        }

        false;
    }

}
