<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\AbstractDependencyFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\GraphBuilderInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\AbstractDependencyTreeReader;

class DependencyGraphBuilder
{

    /**
     * @var GraphBuilderInterface
     */
    protected $graphBuilder;

    /**
     * @var AbstractDependencyTreeReader
     */
    protected $dependencyTreeReader;

    /**
     * @var TreeFilter
     */
    protected $filter;

    /**
     * @param GraphBuilderInterface $graphBuilder
     * @param AbstractDependencyTreeReader $dependencyTreeReader
     * @param TreeFilter $filter
     */
    public function __construct(
        GraphBuilderInterface $graphBuilder,
        AbstractDependencyTreeReader $dependencyTreeReader,
        TreeFilter $filter
    ) {
        $this->graphBuilder = $graphBuilder;
        $this->dependencyTreeReader = $dependencyTreeReader;
        $this->filter = $filter;
    }

    /**
     * @return bool
     */
    public function build()
    {
        $filteredDependencyTree = $this->filter->filter($this->dependencyTreeReader->read());

        return $this->graphBuilder->build($filteredDependencyTree);
    }

}
