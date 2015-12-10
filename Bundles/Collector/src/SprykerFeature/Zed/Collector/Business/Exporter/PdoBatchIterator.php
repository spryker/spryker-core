<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerFeature\Zed\Collector\Business\Model\CountableIteratorInterface;
use SprykerFeature\Zed\Distributor\Business\Distributor\BatchIteratorInterface;

class PdoBatchIterator implements CountableIteratorInterface, BatchIteratorInterface
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
     * @var CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @var TouchQueryContainerInterface
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

    public function __construct(CriteriaBuilderInterface $criteriaBuilder, TouchQueryContainerInterface $touchQueryContainer, $touchType, $chunkSize = 1000)
    {
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
     */
    public function next()
    {
        $this->criteriaBuilder->setOffset($this->offset);
        $this->loadChunk();
        $this->currentKey++;
        $this->isValid = empty($this->currentDataSet) === false;
        $this->offset += $this->chunkSize;
    }

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
