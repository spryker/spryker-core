<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Graph\Adapter;

use phpDocumentor\GraphViz\Edge;
use phpDocumentor\GraphViz\Graph;
use phpDocumentor\GraphViz\Node;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Graph\GraphAdapterInterface;

class PhpDocumentorGraphAdapter implements GraphAdapterInterface
{
    /**
     * @var \phpDocumentor\GraphViz\Graph
     */
    protected $graph;

    /**
     * @return \phpDocumentor\GraphViz\Graph
     */
    private function createPhpDocumentorGraph()
    {
        return new Graph();
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return $this
     */
    public function create($name, array $attributes = [], $directed = true, $strict = true)
    {
        $this->graph = $this->createPhpDocumentorGraph();
        $this->graph->setName($name);

        $type = $this->getType($directed);
        $this->graph->setType($type);
        $this->graph->setStrict($strict);

        $this->addAttributesTo($attributes, $this->graph);

        return $this;
    }

    /**
     * @param bool $directed
     *
     * @return string
     */
    private function getType($directed)
    {
        return $directed ? self::DIRECTED_GRAPH : self::GRAPH;
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param string $group
     *
     * @return $this
     */
    public function addNode($name, $attributes = [], $group = self::DEFAULT_GROUP)
    {
        $node = new Node($name);
        $this->addAttributesTo($attributes, $node);

        if ($group !== self::DEFAULT_GROUP) {
            $graph = $this->getGraphByName($group);
            $graph->setNode($node);
        } else {
            $this->graph->setNode($node);
        }

        return $this;
    }

    /**
     * @param string $fromNode
     * @param string $toNode
     * @param array $attributes
     *
     * @return $this
     */
    public function addEdge($fromNode, $toNode, $attributes = [])
    {
        $edge = new Edge($this->graph->findNode($fromNode), $this->graph->findNode($toNode));
        $this->addAttributesTo($attributes, $edge);

        $this->graph->link($edge);

        return $this;
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return $this
     */
    public function addCluster($name, $attributes = [])
    {
        $graph = $this->getGraphByName($name);

        $this->addAttributesTo($attributes, $graph);

        return $this;
    }

    /**
     * @param string $name
     *
     * @return \phpDocumentor\GraphViz\Graph
     */
    private function getGraphByName($name)
    {
        $name = 'cluster_' . $name;

        if (!$this->graph->hasGraph($name)) {
            $graph = $this->graph->create($name);
            $this->graph->addGraph($graph);
        }

        return $this->graph->getGraph($name);
    }

    /**
     * @param string $type
     * @param string|null $fileName
     *
     * @return string
     */
    public function render($type, $fileName = null)
    {
        $utilTextService = new UtilTextService();

        if ($fileName === null) {
            $fileName = sys_get_temp_dir() . '/' . $utilTextService->generateRandomString(32);
        }
        $this->graph->export($type, $fileName);

        return file_get_contents($fileName);
    }

    /**
     * @param array $attributes
     * @param \phpDocumentor\GraphViz\Edge|\phpDocumentor\GraphViz\Node|\phpDocumentor\GraphViz\Graph $element
     *
     * @return void
     */
    private function addAttributesTo($attributes, $element)
    {
        foreach ($attributes as $attribute => $value) {
            $setter = 'set' . ucfirst($attribute);
            if (strip_tags($value) !== $value) {
                $value = '<' . $value . '>';
            }
            $element->$setter($value);
        }
    }
}
