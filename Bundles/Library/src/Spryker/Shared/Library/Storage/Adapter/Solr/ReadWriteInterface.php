<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\Storage\Adapter\Solr;

use Solarium\Core\Query\QueryInterface;

/**
 * Class ReadWriteInterface
 */
interface ReadWriteInterface
{

    /**
     * @param string $type
     *
     * @return \Solarium\Core\Query\Query
     */
    public function createQuery($type);

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

    /**
     * @return \Solarium\QueryType\Update\Query\Query
     */
    public function createUpdate();

    /**
     * @param \Solarium\Core\Query\QueryInterface $query
     *
     * @return \Solarium\Core\Query\Result\ResultInterface
     */
    public function execute(QueryInterface $query);

    /**
     * @param \Solarium\Core\Query\QueryInterface $query
     *
     * @return \Solarium\QueryType\Update\Result
     */
    public function update(QueryInterface $query);

    /**
     * @param bool $commit
     *
     * @return mixed
     */
    public function deleteAll($commit = true);

}
