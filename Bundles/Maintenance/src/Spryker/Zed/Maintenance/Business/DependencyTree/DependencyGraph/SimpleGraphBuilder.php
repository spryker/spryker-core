<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class SimpleGraphBuilder implements GraphBuilderInterface
{

    /**
     * @var \Spryker\Shared\Graph\GraphInterface
     */
    private $graph;

    /**
     * @param \Spryker\Shared\Graph\GraphInterface $graph
     */
    public function __construct(GraphInterface $graph)
    {
        $this->graph = $graph;
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
