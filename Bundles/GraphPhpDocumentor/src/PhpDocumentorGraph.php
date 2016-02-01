<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Tool\GraphPhpDocumentor;

use phpDocumentor\GraphViz\Graph;

/**
 * PhpDocumentor graph has no option to make a graph strict, if this is fixed on their side this class can be removed
 */
class PhpDocumentorGraph extends Graph
{

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        if (!in_array($type, ['digraph', 'graph', 'strict digraph', 'strict graph', 'subgraph', 'strict subgraph'])) {
            throw new \InvalidArgumentException(
                'The type for a graph must be either "digraph", "graph" or "subgraph" all can also be strict'
            );
        }

        $this->type = $type;

        return $this;
    }

}
