<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Graph\Communication\Plugin;

use Spryker\Shared\Graph\GraphInterface;
use Spryker\Zed\Graph\Communication\Exception\GraphNotInitializedException;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \Spryker\Zed\Graph\GraphConfig getConfig()
 * @method \Spryker\Zed\Graph\Communication\GraphCommunicationFactory getFactory()
 */
class GraphPlugin extends AbstractPlugin implements GraphInterface
{
    /**
     * @var \Spryker\Shared\Graph\GraphInterface
     */
    private $graph;

    /**
     * @api
     *
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return $this
     */
    public function init($name, array $attributes = [], $directed = true, $strict = true)
    {
        $this->graph = $this->getFactory()->createGraph($name, $attributes, $directed, $strict);

        return $this;
    }

    /**
     * @throws \Spryker\Zed\Graph\Communication\Exception\GraphNotInitializedException
     *
     * @return \Spryker\Shared\Graph\GraphInterface
     */
    protected function getGraph()
    {
        if ($this->graph === null) {
            throw new GraphNotInitializedException();
        }

        return $this->graph;
    }

    /**
     * @api
     *
     * @param string $name
     * @param array $attributes
     * @param string $group
     *
     * @return $this
     */
    public function addNode($name, $attributes = [], $group = self::DEFAULT_GROUP)
    {
        $this->getGraph()->addNode($name, $attributes, $group);

        return $this;
    }

    /**
     * @api
     *
     * @param string $fromNode
     * @param string $toNode
     * @param array $attributes
     *
     * @return $this
     */
    public function addEdge($fromNode, $toNode, $attributes = [])
    {
        $this->getGraph()->addEdge($fromNode, $toNode, $attributes);

        return $this;
    }

    /**
     * @api
     *
     * @param string $name
     * @param array $attributes
     *
     * @return $this
     */
    public function addCluster($name, $attributes = [])
    {
        $this->getGraph()->addCluster($name, $attributes);

        return $this;
    }

    /**
     * @api
     *
     * @param string $type
     * @param string|null $fileName
     *
     * @return string
     */
    public function render($type, $fileName = null)
    {
        return $this->getGraph()->render($type, $fileName);
    }
}
