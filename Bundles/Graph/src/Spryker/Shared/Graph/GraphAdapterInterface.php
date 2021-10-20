<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Graph;

interface GraphAdapterInterface extends GraphInterface
{
    /**
     * @var string
     */
    public const GRAPH = 'graph';

    /**
     * @var string
     */
    public const GRAPH_STRICT = 'strict graph';

    /**
     * @var string
     */
    public const DIRECTED_GRAPH = 'digraph';

    /**
     * @var string
     */
    public const DIRECTED_GRAPH_STRICT = 'strict digraph';

    /**
     * @var string
     */
    public const SUB_GRAPH = 'subgraph';

    /**
     * @var string
     */
    public const SUB_GRAPH_STRICT = 'strict subgraph';

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
