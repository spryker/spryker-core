<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Maintenance\Business\DependencyTree\DependencyGraph;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Maintenance\Business\DependencyTree\DependencyTree;

class ExternalGraphBuilder implements GraphBuilderInterface
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
            $this->graph->addNode($dependency[DependencyTree::META_BUNDLE], $this->getFromAttributes($dependency));

            if (!empty($dependency[DependencyTree::META_COMPOSER_NAME])) {
                $this->graph->addNode($dependency[DependencyTree::META_COMPOSER_NAME], $this->getToAttributes($dependency));
            }
        }

        foreach ($dependencyTree as $dependency) {
            if (empty($dependency[DependencyTree::META_COMPOSER_NAME])) {
                continue;
            }
            $this->graph->addEdge($dependency[DependencyTree::META_BUNDLE], $dependency[DependencyTree::META_COMPOSER_NAME], $this->getEdgeAttributes($dependency));
        }

        return $this->graph->render('svg');
    }

    /**
     * @param array $dependency
     *
     * @return array
     */
    private function getFromAttributes(array $dependency)
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
    private function getToAttributes(array $dependency)
    {
        $label = '"' . $dependency[DependencyTree::META_COMPOSER_NAME] . '": "' . $dependency[DependencyTree::META_COMPOSER_VERSION] . '"';
        $attributes = [
            'label' => $label
        ];

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
