<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Library\BatchIterator;

use PDO;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

class PdoBatchIterator implements CountableIteratorInterface
{

    /**
     * @var \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @var \Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = 100;

    /**
     * @var array
     */
    protected $batchData = [];

    /**
     * @param \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     * @param \Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface $connection
     * @param int $chunkSize
     */
    public function __construct(CriteriaBuilderInterface $criteriaBuilder, QueryContainerInterface $connection, $chunkSize = 100)
    {
        $this->criteriaBuilder = $criteriaBuilder;
        $this->queryContainer = $connection;
        $this->chunkSize = $chunkSize;
    }

    /**
     * @return void
     */
    protected function loadChunk()
    {
        $this->criteriaBuilder->setOffset($this->offset);
        $this->criteriaBuilder->setLimit($this->chunkSize);

        $sqlPart = $this->criteriaBuilder->toSqlPart();

        $st = $this->queryContainer
            ->getConnection()
            ->prepare($sqlPart->getSql());
        $st->execute($sqlPart->getParameters());

        $this->batchData = $st->fetchAll(PDO::FETCH_ASSOC);
        $this->offset += $this->chunkSize;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->batchData;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function next()
    {
        $this->loadChunk();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return count($this->batchData);
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
        $this->loadChunk();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        $this->criteriaBuilder->setLimit(null);
        $this->criteriaBuilder->setOffset(null);
        $sqlPart = $this->criteriaBuilder->toSqlPart();

        $countSql = 'SELECT COUNT(*) cnt FROM (' . $sqlPart->getSql() . ') AS v';
        $st = $this->queryContainer->getConnection()->prepare($countSql);
        $st->execute($sqlPart->getParameters());

        return $st->fetchColumn();
    }

}
