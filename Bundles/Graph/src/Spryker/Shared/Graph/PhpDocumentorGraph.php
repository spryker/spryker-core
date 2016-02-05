<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Graph;

use phpDocumentor\GraphViz\Graph;

/**
 * PhpDocumentor graph has no option to make a graph strict, if this is fixed on their side this class can be removed
 */
class PhpDocumentorGraph extends Graph
{

    const GRAPH = 'graph';
    const GRAPH_STRICT = 'strict graph';
    const DIRECTED_GRAPH = 'digraph';
    const DIRECTED_GRAPH_STRICT = 'strict digraph';
    const SUB_GRAPH = 'subgraph';
    const SUB_GRAPH_STRICT = 'strict subgraph';

    const ALLOWED_GRAPH_TYPES = [
        self::GRAPH,
        self::GRAPH_STRICT,
        self::DIRECTED_GRAPH,
        self::DIRECTED_GRAPH_STRICT,
        self::SUB_GRAPH,
        self::SUB_GRAPH_STRICT,
    ];

    const ERROR_WRONG_ARGUMENT_TYPE = 'The type for a graph must be either "%s", "%s" or "%s" all can also be strict';

    /**
     * @param string $type
     *
     * @return self
     */
    public function setType($type)
    {
        if (!in_array($type, self::ALLOWED_GRAPH_TYPES)) {
            throw new \InvalidArgumentException(
                sprintf(self::ERROR_WRONG_ARGUMENT_TYPE, self::GRAPH, self::DIRECTED_GRAPH, self::SUB_GRAPH)
            );
        }

        $this->type = $type;

        return $this;
    }

}
