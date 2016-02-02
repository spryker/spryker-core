<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Tool\Graph;

interface GraphAdapterInterface extends GraphInterface
{

    const GRAPH = 'graph';
    const GRAPH_STRICT = 'strict graph';
    const DIRECTED_GRAPH = 'digraph';
    const DIRECTED_GRAPH_STRICT = 'strict digraph';
    const SUB_GRAPH = 'subgraph';
    const SUB_GRAPH_STRICT = 'strict subgraph';

    /**
     * @param string $name
     * @param array $attributes
     * @param bool $directed
     * @param bool $strict
     *
     * @return self
     */
    public function create($name, array $attributes = [], $directed = true, $strict = true);

}
