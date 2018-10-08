<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Development\Business\DependencyTree\DependencyGraph;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Development\Business\DependencyTree\DependencyTree;

class SimpleGraphBuilder implements GraphBuilderInterface
{
    public const FONT_COLOR = 'fontcolor';
    public const LABEL = 'label';

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
     * @return string
     */
    public function build(array $dependencyTree)
    {
        foreach ($dependencyTree as $dependency) {
            $this->graph->addNode($dependency[DependencyTree::META_MODULE], $this->getNodeAttributes($dependency));
            $this->graph->addNode($dependency[DependencyTree::META_FOREIGN_BUNDLE], $this->getForeignNodeAttributes($dependency));
        }

        foreach ($dependencyTree as $dependency) {
            $this->graph->addEdge($dependency[DependencyTree::META_MODULE], $dependency[DependencyTree::META_FOREIGN_BUNDLE], $this->getEdgeAttributes($dependency));
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
        if ($dependency[DependencyTree::META_MODULE_IS_ENGINE]) {
            $attributes[static::FONT_COLOR] = static::ENGINE_BUNDLE_FONT_COLOR;
            $attributes[static::LABEL] = $dependency[DependencyTree::META_MODULE] . static::ENGINE_BUNDLE_INFO_TEXT;
        }

        return $attributes;
    }

    /**
     * @param array $dependency
     *
     * @return array
     */
    private function getForeignNodeAttributes(array $dependency)
    {
        $attributes = [];
        if ($dependency[DependencyTree::META_FOREIGN_BUNDLE_IS_ENGINE]) {
            $attributes[static::FONT_COLOR] = static::ENGINE_BUNDLE_FONT_COLOR;
            $attributes[static::LABEL] = $dependency[DependencyTree::META_FOREIGN_BUNDLE] . static::ENGINE_BUNDLE_INFO_TEXT;
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
            $attributes[static::FONT_COLOR] = static::ENGINE_BUNDLE_FONT_COLOR;
        }

        return $attributes;
    }
}
