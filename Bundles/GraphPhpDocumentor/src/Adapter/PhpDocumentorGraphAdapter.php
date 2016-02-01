<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Tool\GraphPhpDocumentor\Adapter;

use phpDocumentor\GraphViz\Edge;
use phpDocumentor\GraphViz\Graph;
use phpDocumentor\GraphViz\Node;
use Spryker\Tool\Graph\GraphAdapterInterface;
use Spryker\Tool\GraphPhpDocumentor\PhpDocumentorGraph;

class PhpDocumentorGraphAdapter implements GraphAdapterInterface
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
        $this->graph = new PhpDocumentorGraph();
        $this->graph->setName($name);

        if ($strict) {
            $this->graph->setType($directed ? 'strict digraph' : 'strict graph');
        } else {
            $this->graph->setType($directed ? 'digraph' : 'graph');
        }

        $this->addAttributesTo($attributes, $this->graph);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param string $group
     *
     * @return void
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
    }

    /**
     * @param string $fromNode
     * @param string $toNode
     * @param array $attributes
     *
     * @return void
     */
    public function addEdge($fromNode, $toNode, $attributes = [])
    {
        $edge = new Edge($this->graph->findNode($fromNode), $this->graph->findNode($toNode));
        $this->addAttributesTo($attributes, $edge);

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

        $this->addAttributesTo($attributes, $graph);
    }

    /**
     * @param string $name
     *
     * @return Graph
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
     * @param string $fileName
     *
     * @throws \phpDocumentor\GraphViz\Exception
     *
     * @return string
     */
    public function render($type, $fileName = null)
    {
        if ($fileName === null) {
            $fileName = sys_get_temp_dir() . '/' . uniqid();
        }
        $this->graph->export($type, $fileName);

        return file_get_contents($fileName);
    }

    /**
     * @param array $attributes
     * @param Edge|Node|Graph $element
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
