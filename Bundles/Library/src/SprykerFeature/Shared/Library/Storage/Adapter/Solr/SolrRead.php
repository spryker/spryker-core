<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\Solr;

use Solarium\Core\Query\QueryInterface;

/**
 * Class SolrRead
 *
 * @property \Solarium\Client $resource
 */
class SolrRead extends Solr implements ReadInterface
{

    /**
     * @return \Solarium\QueryType\Select\Query\Query
     */
    public function createSelect()
    {
        return $this->getResource()->createSelect();
    }

    /**
     * @param QueryInterface $query
     *
     * @return \Solarium\QueryType\Select\Result\Result
     */
    public function select(QueryInterface $query)
    {
        return $this->getResource()->select($query);
    }

    /**
     * @return int
     */
    public function getNumDocs()
    {
        $select = $this->getResource()->createSelect();
        $result = $this->getResource()->select($select);

        return $result->getNumFound();
    }

}
