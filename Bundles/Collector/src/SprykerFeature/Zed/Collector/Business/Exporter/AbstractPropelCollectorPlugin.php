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
        
        $batchCollection = $this->getBatchIterator($query);

        $totalCount = $result->getTotalCount();
        foreach ($batchCollection as $batch) {
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
        
        if (null === $query) {
            return; //TODO: stop processing here, if not implemented properly yet
        }

        $batchCollection = $this->getBatchIterator($query);

        $totalCount = $result->getTotalCount();
        foreach ($batchCollection as $batch) {
            $collectedData = $this->processDataForDeletion($batch, $locale);
            $count = count($collectedData);
            
            $dataWriter->delete($collectedData);

            $result->increaseProcessedCount($count);
            $result->increaseDeletedCount($count);
            $totalCount += $count;
        }

        $result->setTotalCount($totalCount);

        $this->flushDeletedItems($baseQuery, $locale);
        $this->flushDeletedTouchItems();
    }

    /**
     * Collector specific cleanup of deleted items
     * 
     * @param SpyTouchQuery $deletionQuery
     * @param LocaleTransfer $locale
     */
    protected function flushDeletedItems(SpyTouchQuery $deletionQuery, LocaleTransfer $locale)
    {

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
