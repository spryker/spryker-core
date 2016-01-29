<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Zed\Library\GraphViz\Adapter\PhpDocumentorGraph;
use Spryker\Zed\Library\GraphViz\GraphViz;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class SimpleGraphBuilder implements GraphBuilderInterface
{

    /**
     * @var GraphViz
     */
    private $graph;

    public function __construct()
    {
        $adapter = new PhpDocumentorGraph();
        $this->graph = new GraphViz($adapter, 'Bundle Dependencies');
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
            $this->graph->addEdge([$dependency[DependencyTree::META_BUNDLE] => $dependency[DependencyTree::META_FOREIGN_BUNDLE]], $this->getEdgeAttributes($dependency));
        }

        $fileName = sys_get_temp_dir() . '/graph';
        $this->graph->render('svg', $fileName);

        echo file_get_contents($fileName);
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
