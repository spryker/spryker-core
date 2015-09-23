<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use SprykerEngine\Zed\Touch\Persistence\Propel\Map\SpyTouchTableMap;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouch;
use SprykerEngine\Zed\Touch\Persistence\Propel\SpyTouchQuery;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;

abstract class AbstractPropelCollectorPlugin
{

    const TOUCH_EXPORTER_ID = 'exporter_touch_id';

    /**
     * @var int
     */
    private $chunkSize = 100;

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function run(SpyTouchQuery $baseQuery,
                        LocaleTransfer $locale,
                        BatchResultInterface $result,
                        WriterInterface $dataWriter,
                        TouchUpdaterInterface $touchUpdater
    )
    {
        $itemType = $baseQuery->get(SpyTouchTableMap::COL_ITEM_TYPE);

        $this->runDeletion($locale, $result, $dataWriter, $touchUpdater, $itemType);
        $this->runInsertion($baseQuery, $locale, $result, $dataWriter, $touchUpdater);
    }

    /**
     * Remove orphans (marked as 'deleted' and without any keys)
     *
     * @param $itemType
     * @return int
     */
    public function removeDeletedRows($itemType)
    {
        $deleteQuery = new SpyTouchQuery(); // FIXME Should be done via query container
        $deleteQuery->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED);
        $deleteQuery->filterByItemType($itemType);
        $deleteQuery->leftJoinTouchSearch();
        $deleteQuery->leftJoinTouchStorage();
        $deleteQuery->where('spy_touch_search.fk_touch IS NULL');
        $deleteQuery->where('spy_touch_storage.fk_touch IS NULL');

        $entities = $deleteQuery->find();
        $deletedCount = 0;
        foreach ($entities as $e) {
            $e->delete();
            $deletedCount++;
        }

        return $deletedCount;
    }

    /**
     * TODO needs cleanup!!!
     * @param $itemType
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     * @param $locale
     */
    public function delete($itemType, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, $locale)
    {

        $deleteQuery = new SpyTouchQuery(); // FIXME Should be done via query container
        $deleteQuery->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED);
        $deleteQuery->filterByItemType($itemType);
        $entities = $deleteQuery->find();


        foreach ($entities as $entity) {
            /* @var $entity SpyTouch */

            $keyEntity = $touchUpdater->getKeyById($entity->getIdTouch(), $locale);
            if (!empty($keyEntity)) {
                $key = $keyEntity->getKey();
                $dataWriter->delete([$key]);
                $keyEntity->delete();
            }
        }

    }

    public function postRun(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater)
    {
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     *
     * @return SpyTouchQuery
     */
    abstract protected function createQuery(SpyTouchQuery $baseQuery, LocaleTransfer $locale);

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
     * @param TouchUpdaterSet $touchUpdaterSet
     */
    abstract protected function processData($resultSet, LocaleTransfer $locale, TouchUpdaterSet $touchUpdaterSet);

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

    /**
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     * @param $itemType
     */
    protected function runDeletion(LocaleTransfer $locale, BatchResultInterface $result, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, $itemType)
    {
        $this->delete($itemType, $dataWriter, $touchUpdater, $locale);

        $deletedCount = $this->removeDeletedRows($itemType);
        $result->setDeletedCount($deletedCount);
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    protected function runInsertion(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater)
    {
        $query = $this->createQuery($baseQuery, $locale);

        $batchCollection = $this->getBatchIterator($query);

        $totalCount = $result->getTotalCount();
        foreach ($batchCollection as $batch) {

            $touchUpdaterSet = new TouchUpdaterSet();
            $collectedData = $this->processData($batch, $locale, $touchUpdaterSet);
            $count = count($collectedData);

            $touchUpdater->updateMulti($touchUpdaterSet, $locale->getIdLocale());

            $dataWriter->write($collectedData, $this->getTouchItemType());

            $result->increaseProcessedCount($count);
            $totalCount += $count;
        }

        $result->setTotalCount($totalCount);
    }

}
