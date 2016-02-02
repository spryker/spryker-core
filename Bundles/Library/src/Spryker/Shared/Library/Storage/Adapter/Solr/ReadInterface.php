<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\Library\Storage\Adapter\Solr;

use Solarium\Core\Query\QueryInterface;

/**
 * Class ReadInterface
 */
interface ReadInterface
{

    /**
     * @return \Solarium\QueryType\Select\Query\Query
     */
    public function createSelect();

    /**
     * @param \Solarium\Core\Query\QueryInterface $query
     *
     * @return \Solarium\QueryType\Select\Result\Result
     */
    public function select(QueryInterface $query);

    /**
     * @return int
     */
    public function getNumDocs();

}
