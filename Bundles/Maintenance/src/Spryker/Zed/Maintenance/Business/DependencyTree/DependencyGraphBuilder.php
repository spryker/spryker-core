<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyFilter\TreeFilter;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\GraphBuilderInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTreeReader\DependencyTreeReaderInterface;

class DependencyGraphBuilder
{

    /**
     * @var GraphBuilderInterface
     */
    private $graphBuilder;

    /**
     * @var DependencyTreeReaderInterface
     */
    private $dependencyTreeReader;

    /**
     * @var TreeFilter
     */
    private $filter;

    /**
     * @param GraphBuilderInterface $graphBuilder
     * @param DependencyTreeReaderInterface $dependencyTreeReader
     * @param TreeFilter $filter
     */
    public function __construct(
        GraphBuilderInterface $graphBuilder,
        DependencyTreeReaderInterface $dependencyTreeReader,
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
