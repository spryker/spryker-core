<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Library\GraphViz\Adapter;

use phpDocumentor\GraphViz\Edge;
use phpDocumentor\GraphViz\Graph;
use phpDocumentor\GraphViz\Node;
use Spryker\Zed\Library\GraphViz\GraphVizInterface;

class PhpDocumentorGraph implements AdapterInterface
{

    /**
     * @var Graph
     */
    private $graph;

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return void
     */
    public function create($name, array $attributes = [], $directed = true, $strict = true)
    {
        $this->graph = new Graph();
        $this->graph->setName($name);
        $this->graph->setType($directed ? 'digraph' : 'graph');

        foreach ($attributes as $attribute => $value) {
            $setter = 'set' . ucfirst($attribute);
            $this->graph->$setter($value);
        }
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param string $group
     *
     * @return void
     */
    public function addNode($name, $attributes = [], $group = 'default')
    {
        $node = new Node($name);
        foreach ($attributes as $attribute => $value) {
            $setter = 'set' . ucfirst($attribute);
            $node->$setter($value);
        }

        if ($group !== 'default') {
            $graph = $this->getGraphByName($group);
            $graph->setNode($node);
        } else {
            $this->graph->setNode($node);
        }
    }

    /**
     * @param string $edge
     * @param array $attributes
     *
     * @return void
     */
    public function addEdge($edge, $attributes = [])
    {
        $fromName = key($edge);
        $toName = $edge[$fromName];

        $edge = new Edge($this->graph->findNode($fromName), $this->graph->findNode($toName));
        foreach ($attributes as $attribute => $value) {
            $setter = 'set' . ucfirst($attribute);
            $edge->$setter($value);
        }

        $this->graph->link($edge);
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return void
     */
    public function addCluster($name, $attributes = [])
    {
        $graph = $this->getGraphByName($name);

        foreach ($attributes as $attribute => $value) {
            $setter = 'set' . ucfirst($attribute);
            $graph->$setter($value);
        }
    }


    /**
     * @param string $name
     *
     * @return Graph
     */
    private function getGraphByName($name)
    {
        if (!$this->graph->hasGraph($name)) {
            $this->graph->create($name);
        }

        return $this->graph->getGraph($name);
    }

    /**
     * @param string $type
     * @param string $fileName
     *
     * @throws \phpDocumentor\GraphViz\Exception
     *
     * @return Graph
     */
    public function render($type, $fileName)
    {
        return $this->graph->export($type, $fileName);
    }

}
