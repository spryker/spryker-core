<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Library\GraphViz;

interface GraphVizInterface
{

    /**
     * @param string $name
     * @param array $attributes
     * @param string $group
     *
     * @return self
     */
    public function addNode($name, $attributes = [], $group = 'default');

    /**
     * @param string $edge
     * @param array $attributes
     *
     * @return self
     */
    public function addEdge($edge, $attributes = []);

    /**
     * @param string $name
     * @param array $attributes
     *
     * @return self
     */
    public function addCluster($name, $attributes = []);

    /**
     * @param string $type
     * @param string $fileName
     *
     * @return void
     */
    public function render($type, $fileName);

}
