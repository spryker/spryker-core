<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Zed\Library\GraphViz\Adapter\AdapterInterface;
use Spryker\Zed\Library\GraphViz\Adapter\PhpDocumentorGraphAdapter;
use Spryker\Zed\Library\GraphViz\GraphViz;
use Spryker\Zed\Library\GraphViz\GraphVizInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class SimpleGraphBuilder implements GraphBuilderInterface
{

    /**
     * @var GraphVizInterface
     */
    private $graph;

    /**
     * @param GraphVizInterface $graphViz
     */
    public function __construct(GraphVizInterface $graphViz)
    {
        $this->graph = $graphViz;
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
            $this->graph->addNode($dependency[DependencyTree::META_FOREIGN_BUNDLE], $this->getNodeAttributes($dependency));
        }

        foreach ($dependencyTree as $dependency) {
            $this->graph->addEdge($dependency[DependencyTree::META_BUNDLE], $dependency[DependencyTree::META_FOREIGN_BUNDLE], $this->getEdgeAttributes($dependency));
        }

        return $this->graph->render('svg');
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
