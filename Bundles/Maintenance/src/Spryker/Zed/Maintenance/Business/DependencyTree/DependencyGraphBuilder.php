<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree;

use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\GraphBuilderInterface;

class DependencyGraphBuilder
{

    /**
     * @var \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\GraphBuilderInterface
     */
    private $graphBuilder;

    /**
     * @var array
     */
    private $dependencyTree;

    /**
     * @param \Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph\GraphBuilderInterface $graphBuilder
     * @param array $dependencyTree
     */
    public function __construct(
        GraphBuilderInterface $graphBuilder,
        array $dependencyTree
    ) {
        $this->graphBuilder = $graphBuilder;
        $this->dependencyTree = $dependencyTree;
    }

    /**
     * @return bool
     */
    public function build()
    {
        return $this->graphBuilder->build($this->dependencyTree);
    }

}
