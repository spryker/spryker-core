<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Graph;

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
     * @return $this
     */
    public function create($name, array $attributes = [], $directed = true, $strict = true);
}
