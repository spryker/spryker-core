<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
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
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     */
    public function run(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result, WriterInterface $dataWriter)
    {
        $query = $this->createQuery($baseQuery, $locale);
        
        $resultCollection = $this->getBatchIterator($query);

        $totalCount = $result->getTotalCount();
        foreach ($resultCollection as $batch) {
            $collectedData = $this->processData($batch, $locale);
            $count = count($collectedData);

            $dataWriter->write($collectedData, $this->getTouchItemType());

            $result->increaseProcessedCount($count);
            $totalCount += $count;
        }

        $result->setTotalCount($totalCount);
    }
    
    public function postRun(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result, WriterInterface $dataWriter)
    {
        $query = $this->createQueryForDeletion($baseQuery, $locale);

        $resultCollection = $this->getBatchIterator($query);

        $totalCount = 0;
        foreach ($resultCollection as $resultItem) {
            $collectedData = $this->processDataForDeletion($resultItem, $locale);
            $count = count($collectedData);
            
            $dataWriter->delete($collectedData);

            $result->increaseProcessedCount($count);
            $result->increaseDeletedCount($count);
            $totalCount += $count;
        }

        $result->setTotalCount($totalCount);
        
        $this->flushDeletedTouchItems();
    }
    
    protected function flushDeletedTouchItems()
    {
        $query = new SpyTouchQuery();
        $touchItemsToDelete = $query->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->find();
        
        foreach ($touchItemsToDelete as $touchItem) {
            $touchItem->delete();
        }
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     *
     * @return SpyTouchQuery
     */
    abstract protected function createQuery(SpyTouchQuery $baseQuery, LocaleTransfer $locale);
    abstract protected function createQueryForDeletion(SpyTouchQuery $baseQuery, LocaleTransfer $locale);

    /**
     * @param $baseQuery
     *
     * @return BatchIterator
     */
    protected function getBatchIterator($baseQuery)
    {
        return new BatchIterator($baseQuery, $this->chunkSize);
    }

    /**
     * @param array $resultSet
     * @param LocaleTransfer $locale
     *
     * @return array
     */
    abstract protected function processData($resultSet, LocaleTransfer $locale);
    abstract protected function processDataForDeletion($resultSet, LocaleTransfer $locale);

    /**
     * @return string
     */
    abstract protected function getTouchItemType();

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * @param int $chunkSize
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
    }

}
