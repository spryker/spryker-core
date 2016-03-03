<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Persistence;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;
use Spryker\Zed\Collector\Business\Model\CountableIteratorInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;

class PdoBatchIterator implements CountableIteratorInterface
{

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = 1000;

    /**
     * @var string
     */
    protected $touchType;

    /**
     * @var \Everon\Component\CriteriaBuilder\CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var int
     */
    protected $currentKey = 0;

    /**
     * @var bool
     */
    protected $isValid = true;

    /**
     * @var array
     */
    protected $currentDataSet = [];

    /**
     * @param \Everon\Component\CriteriaBuilder\CriteriaBuilderInterface $criteriaBuilder
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     * @param int $touchType
     * @param int $chunkSize
     */
    public function __construct(
        CriteriaBuilderInterface $criteriaBuilder,
        TouchQueryContainerInterface $touchQueryContainer,
        $touchType,
        $chunkSize = 1000
    ) {
        $this->criteriaBuilder = $criteriaBuilder;
        $this->touchQueryContainer = $touchQueryContainer;
        $this->touchType = $touchType;
        $this->chunkSize = $chunkSize;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->currentDataSet;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function next()
    {
        $this->criteriaBuilder->setOffset($this->offset);
        $this->loadChunk();
        $this->currentKey++;
        $this->isValid = empty($this->currentDataSet) === false;
        $this->offset += $this->chunkSize;
    }

    /**
     * @return void
     */
    protected function loadChunk()
    {
        $this->criteriaBuilder->setLimit($this->chunkSize);

        $sqlPart = $this->criteriaBuilder->toSqlPart();

        $st = $this->touchQueryContainer->getConnection()->prepare($sqlPart->getSql());
        $st->execute($sqlPart->getParameters());

        $this->currentDataSet = $st->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->currentKey;
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->isValid;
    }

    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function rewind()
    {
        $this->currentKey = 0;
        $this->offset = 0;
        $this->next();
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
        $st = $this->touchQueryContainer->getConnection()->prepare($countSql);
        $st->execute($sqlPart->getParameters());

        return $st->fetchColumn();
    }

}
