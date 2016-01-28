<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Zed\Library\Service\GraphViz;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class SimpleGraphBuilder implements GraphBuilderInterface
{

    /**
     * @var GraphViz
     */
    private $graph;

    public function __construct()
    {
        $this->graph = new GraphViz(true, [], 'Bundle Dependencies', true, true);
    }

    /**
     * @param array $dependencyTree
     *
     * @return bool
     */
    public function build(array $dependencyTree)
    {
        foreach ($dependencyTree as $dependency) {
            $this->graph->addNode($dependency[DependencyTree::META_BUNDLE], $this->getNodeAttributes($dependency));
        }

        foreach ($dependencyTree as $dependency) {
            $this->graph->addEdge([$dependency[DependencyTree::META_BUNDLE] => $dependency[DependencyTree::META_FOREIGN_BUNDLE]], $this->getEdgeAttributes($dependency));
        }

        return $this->graph->image('svg', 'dot');
    }

    /**
     * @param array $dependency
     *
     * @return array
     */
    private function getNodeAttributes(array $dependency)
    {
        $attributes = [];
        if ($dependency[DependencyTree::META_BUNDLE_IS_ENGINE]) {
            $attributes['fontcolor'] = 'red';
        }

        return $attributes;
    }

    /**
     * @param array $dependency
     *
     * @return array
     */
    private function getEdgeAttributes(array $dependency)
    {
        $attributes = [];
        if ($dependency[DependencyTree::META_FOREIGN_BUNDLE_IS_ENGINE]) {
            $attributes['fontcolor'] = 'red';
        }

        return $attributes;
    }

}
