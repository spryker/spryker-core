<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;

abstract class AbstractPropelCollectorPlugin
{

    /**
     * @var int
     */
    private $chunkSize = 100;

    /**
     * @return string
     */
    abstract protected function getTouchItemType();

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     *
     * @return SpyTouchQuery
     */
    abstract protected function createQuery(SpyTouchQuery $baseQuery, LocaleTransfer $locale);

    /**
     * @param array $resultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    abstract protected function processData($resultSet, LocaleTransfer $locale);

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     */
    public function run(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result, WriterInterface $dataWriter)
    {
        $query = $this->createQuery($baseQuery, $locale);

        $resultSets = $this->getBatchIterator($query);

        $totalCount = 0;
        foreach ($resultSets as $resultSet) {
            $collectedData = $this->processData($resultSet, $locale);
            $count = count($collectedData);

            $dataWriter->write($collectedData, $this->getTouchItemType());

            $result->increaseProcessedCount($count);
            $totalCount += $count;
        }

        $result->setTotalCount($totalCount);
    }

    /**
     * @param int $chunkSize
     */
    public function setChunkSize($chunkSize) {
        $this->chunkSize = $chunkSize;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * @param $baseQuery
     *
     * @return BatchIterator
     */
    protected function getBatchIterator($baseQuery)
    {
        return new BatchIterator($baseQuery, $this->chunkSize);
    }

}
