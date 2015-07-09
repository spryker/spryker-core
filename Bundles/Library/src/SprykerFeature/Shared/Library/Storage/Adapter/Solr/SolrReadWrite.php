<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Storage\Adapter\Solr;

use Solarium\Core\Query\QueryInterface;

/**
 * Class SolrReadWrite
 */
class SolrReadWrite extends SolrRead implements ReadWriteInterface
{

    /**
     * @return \Solarium\QueryType\Update\Query\Query
     */
    public function createUpdate()
    {
        return $this->getResource()->createUpdate();
    }

    /**
     * @param string $type
     * @param array $options
     *
     * @return \Solarium\Core\Query\Query
     */
    public function createQuery($type, $options = null)
    {
        return $this->getResource()->createQuery($type, $options);
    }

    /**
     * @param QueryInterface $query
     *
     * @return \Solarium\QueryType\Update\Result
     */
    public function update(QueryInterface $query)
    {
        return $this->getResource()->update($query);
    }

    /**
     * @param QueryInterface $query
     *
     * @return \Solarium\Core\Query\Result\ResultInterface
     */
    public function execute(QueryInterface $query)
    {
        return $this->getResource()->execute($query);
    }

    /**
     * @param bool $commit
     *
     * @return mixed|\Solarium\QueryType\Update\Result
     */
    public function deleteAll($commit = true)
    {
        $update = $this->getResource()->createUpdate();
        $update->addDeleteQuery('*:*');
        if ($commit) {
            $update->addCommit();
        }
        $result = $this->getResource()->update($update);

        return $result;
    }

}
