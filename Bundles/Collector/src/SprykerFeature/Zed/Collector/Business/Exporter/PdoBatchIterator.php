<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;
use Propel\Runtime\Connection\ConnectionInterface;
use SprykerFeature\Zed\Collector\Business\Model\CountableIteratorInterface;

class PdoBatchIterator implements CountableIteratorInterface
{

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $chunkSize = 100;

    /**
     * @var CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @var ConnectionInterface
     */
    protected $connection;

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
     * @param CriteriaBuilderInterface $criteriaBuilder
     * @param int $chunkSize
     */
    public function __construct(CriteriaBuilderInterface $criteriaBuilder, ConnectionInterface $connection, $chunkSize = 100)
    {
        $this->criteriaBuilder = $criteriaBuilder;
        $this->connection = $connection;
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

        $st = $this->connection->prepare($sqlPart->getSql());
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

        $countSql = 'SELECT COUNT(*) cnt FROM (' . $sqlPart->getSql() . ') as v';
        $st = $this->connection->prepare($countSql);
        $st->execute($sqlPart->getParameters());

        return $st->fetchColumn();
    }

}
