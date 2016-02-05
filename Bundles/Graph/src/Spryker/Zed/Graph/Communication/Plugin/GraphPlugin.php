<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Graph\Communication\Plugin;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Graph\GraphConfig getConfig()
 * @method \Spryker\Zed\Graph\Communication\GraphCommunicationFactory getFactory()
 */
class GraphPlugin extends AbstractPlugin implements GraphInterface
{

    /**
     * @var GraphInterface
     */
    private $graph;

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     */
    public function __construct($name, array $attributes = [], $directed = true, $strict = true)
    {
        $this->graph = $this->getFactory()->createGraph($name, $attributes, $directed, $strict);
    }

    /**
     * @return \Spryker\Shared\Graph\GraphInterface
     */
    protected function getGraph()
    {
        return $this->graph;
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param string $group
     *
     * @return self
     */
    public function addNode($name, $attributes = [], $group = self::DEFAULT_GROUP)
    {
        $this->getGraph()->addNode($name, $attributes, $group);

        return $this;
    }

    /**
     * @param string $fromNode
     * @param string $toNode
     * @param array $attributes
     *
     * @return self
     */
    public function addEdge($fromNode, $toNode, $attributes = [])
    {
        $this->getGraph()->addEdge($fromNode, $toNode, $attributes);

        return $this;
    }

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return self
     */
    public function addCluster($name, $attributes = [])
    {
        $this->getGraph()->addCluster($name, $attributes);

        return $this;
    }

    /**
     * @param string $type
     * @param null $fileName
     *
     * @return string
     */
    public function render($type, $fileName = null)
    {
        return $this->getGraph()->render($type, $fileName);
    }

}
