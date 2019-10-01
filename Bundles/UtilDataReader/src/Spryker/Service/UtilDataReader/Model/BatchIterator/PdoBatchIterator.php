<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Service\UtilDataReader\Model\BatchIterator;

use PDO;
use Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

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
     * @var string|null
     */
    protected $orderBy;

    /**
     * @var string|null
     */
    protected $orderByDirection;

    /**
     * @param \Spryker\Shared\SqlCriteriaBuilder\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     * @param \Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface $connection
     * @param int $chunkSize
     * @param string|null $orderBy
     * @param string|null $orderByDirection
     */
    public function __construct(
        CriteriaBuilderInterface $criteriaBuilder,
        QueryContainerInterface $connection,
        $chunkSize = 100,
        $orderBy = null,
        $orderByDirection = null
    ) {
        $this->criteriaBuilder = $criteriaBuilder;
        $this->queryContainer = $connection;
        $this->chunkSize = $chunkSize;
        $this->orderBy = $orderBy;
        $this->orderByDirection = $orderByDirection;
    }

    /**
     * @return void
     */
    protected function loadChunk()
    {
        $this->criteriaBuilder->setOffset($this->offset);
        $this->criteriaBuilder->setLimit($this->chunkSize);

        if ($this->orderBy) {
            if (!$this->orderByDirection) {
                $this->orderByDirection = Criteria::ASC;
            }
            $this->criteriaBuilder->setOrderBy([$this->orderBy => $this->orderByDirection]);
        }

        $sqlPart = $this->criteriaBuilder->toSqlPart();

        $st = $this->queryContainer
            ->getConnection()
            ->prepare($sqlPart->getSql());
        $st->execute($sqlPart->getParameters());

        $this->batchData = $st->fetchAll(PDO::FETCH_ASSOC);
        $this->offset += $this->chunkSize;
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return $this->batchData;
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        $this->loadChunk();
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return $this->offset;
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return count($this->batchData);
    }

    /**
     * {@inheritDoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->offset = 0;
        $this->loadChunk();
    }

    /**
     * @inheritDoc
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
