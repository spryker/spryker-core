<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Library\GraphViz;

use Spryker\Zed\Library\GraphViz\Adapter\AdapterInterface;

class GraphViz
{

    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * @param AdapterInterface $adapter
     * @param $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     */
    public function __construct(AdapterInterface $adapter, $name, array $attributes = [], $directed = true, $strict = true)
    {
        $this->adapter = $adapter;
        $this->adapter->create($name, $attributes, $directed, $strict);
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param string $group
     *
     * @return self
     */
    public function addNode($name, $attributes = [], $group = 'default')
    {
        $this->adapter->addNode($name, $attributes, $group);

        return $this;
    }

    /**
     * @param string $edge
     * @param array $attributes
     *
     * @return self
     */
    public function addEdge($edge, $attributes = [])
    {
        $this->adapter->addEdge($edge, $attributes);

        return $this;
    }

    /**
     * @param string $type
     * @param string $fileName
     *
     * @return void
     */
    public function render($type, $fileName)
    {
        $this->adapter->render($type, $fileName);
    }

}
