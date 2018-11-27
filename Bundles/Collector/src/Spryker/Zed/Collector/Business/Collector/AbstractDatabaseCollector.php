<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchSearchTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchStorageTableMap;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Shared\Collector\CollectorConstants;
use Spryker\Shared\Config\Config;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractDatabaseCollector extends AbstractCollector implements DatabaseCollectorInterface
{
    public const ID_TOUCH = 'idTouch';

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
     */
    public function collectDataFromDatabase(SpyTouchQuery $touchQuery, LocaleTransfer $locale)
    {
        $this->prepareCollectorScope($touchQuery, $locale);
        $batchCollection = $this->generateBatchIterator();

        return $batchCollection;
    }

    /**
     * @param \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface $batchCollection
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $storeReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function exportDataToStore(
        CountableIteratorInterface $batchCollection,
        TouchUpdaterInterface $touchUpdater,
        BatchResultInterface $batchResult,
        ReaderInterface $storeReader,
        WriterInterface $storeWriter,
        LocaleTransfer $locale,
        OutputInterface $output
    ) {
        if ($batchCollection->count() === 0) {
            return;
        }
        $output->write(PHP_EOL);

        $progressBar = $this->startProgressBar($batchCollection, $batchResult, $output);

        foreach ($batchCollection as $batch) {
            $this->processBatchForExport(
                $batch,
                $progressBar,
                $locale,
                $touchUpdater,
                $batchResult,
                $storeWriter
            );
        }

        $progressBar->finish();
    }

    /**
     * @param array $batch
     * @param \Symfony\Component\Console\Helper\ProgressBar $progressBar
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     *
     * @return void
     */
    protected function processBatchForExport(
        array $batch,
        ProgressBar $progressBar,
        LocaleTransfer $locale,
        TouchUpdaterInterface $touchUpdater,
        BatchResultInterface $batchResult,
        WriterInterface $storeWriter
    ) {
        $batchItemCount = count($batch);
        $progressBar->advance($batchItemCount);

        $touchUpdaterSet = new TouchUpdaterSet(CollectorConfig::COLLECTOR_TOUCH_ID);

        $exportSubBatch = $this->collectData($batch, $locale, $touchUpdaterSet);
        $exportedItemCount = $this->exportBatch($exportSubBatch, $locale, $touchUpdaterSet, $storeWriter, $touchUpdater);

        $deletedItemCount = 0;
        if ($this->hasUnprocessedItem($batchItemCount, $exportedItemCount)) {
            $expiredSubBatch = $this->collectExpiredData($batch, $locale);
            $deletedItemCount = $this->deleteBatch($expiredSubBatch, $locale, $storeWriter, $touchUpdater);
        }

        $batchResult->increaseProcessedCount($exportedItemCount + $deletedItemCount);
    }

    /**
     * @param int $batchItemCount
     * @param int $exportedItemCount
     *
     * @return bool
     */
    protected function hasUnprocessedItem($batchItemCount, $exportedItemCount)
    {
        return $batchItemCount > $exportedItemCount;
    }

    /**
     * @param array $batch
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     *
     * @return int
     */
    protected function exportBatch($batch, LocaleTransfer $locale, TouchUpdaterSet $touchUpdaterSet, WriterInterface $storeWriter, TouchUpdaterInterface $touchUpdater)
    {
        $batchSize = count($batch);
        if ($batchSize === 0) {
            return 0;
        }

        $touchUpdater->bulkUpdate(
            $touchUpdaterSet,
            $locale->getIdLocale(),
            $this->getCurrentStore()->getIdStore(),
            $this->touchQueryContainer->getConnection()
        );
        $storeWriter->write($batch);

        return $batchSize;
    }

    /**
     * @param array $batch Keys are expired touch keys.
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     *
     * @return int
     */
    protected function deleteBatch(array $batch, LocaleTransfer $locale, WriterInterface $storeWriter, TouchUpdaterInterface $touchUpdater)
    {
        $batchSize = count($batch);
        if ($batchSize === 0) {
            return 0;
        }

        $touchKeys = array_keys($batch);

        $touchUpdater->deleteTouchKeyEntities($touchKeys, $locale->getIdLocale());
        $storeWriter->delete(array_flip($touchKeys));

        return $batchSize;
    }

    /**
     * @param \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface $batchCollection
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    protected function startProgressBar(
        CountableIteratorInterface $batchCollection,
        BatchResultInterface $batchResult,
        OutputInterface $output
    ) {
        $this->displayProgressWhileCountingBatchCollectionSize($output);
        $totalCount = $batchCollection->count();
        $batchResult->setTotalCount($totalCount);

        $progressBar = $this->generateProgressBar($output, $totalCount);
        $progressBar->start();
        $progressBar->advance(0);

        return $progressBar;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param string $itemType
     *
     * @return int
     */
    public function deleteDataFromStore(
        TouchUpdaterInterface $touchUpdater,
        WriterInterface $storeWriter,
        $itemType
    ) {
        $touchUpdaterSet = new TouchUpdaterSet(CollectorConfig::COLLECTOR_TOUCH_ID);
        $batchCount = 1;
        $offset = 0;
        $deletedCount = 0;

        while ($batchCount > 0) {
            $entityCollection = $this->getTouchCollectionToDelete($itemType, $offset);
            $batchItemIds = $this->collectItemIds($entityCollection);
            $batchCount = count($entityCollection);

            if ($batchCount > 0) {
                $deletedCount += $batchCount;
                $offset += $this->chunkSize;

                $keysToDelete = $this->getKeysToDeleteAndUpdateTouchUpdaterSet(
                    $entityCollection,
                    $touchUpdater->getTouchKeyColumnName(),
                    $touchUpdaterSet
                );

                if ($keysToDelete) {
                    $this->deleteTouchKeyEntities($keysToDelete, $touchUpdater);
                    $storeWriter->delete($keysToDelete);
                }
            }

            if ($this->isTouchDeleteCleanupActive()) {
                $this->deleteObsoleteTouchRecords($itemType, $batchItemIds);
            }
        }

        return $deletedCount;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @return void
     */
    public function setLocale(LocaleTransfer $localeTransfer)
    {
        $this->locale = $localeTransfer;
    }

    /**
     * @param string[] $keysToDelete
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     *
     * @return void
     */
    protected function deleteTouchKeyEntities(array $keysToDelete, TouchUpdaterInterface $touchUpdater)
    {
        $touchUpdater->deleteTouchKeyEntities(
            array_keys($keysToDelete),
            $this->locale->getIdLocale()
        );
    }

    /**
     * @param array $entityCollection
     *
     * @return array
     */
    protected function collectItemIds(array $entityCollection)
    {
        $itemIds = [];
        foreach ($entityCollection as $entity) {
            $itemIds[] = $entity['item_id'];
        }

        return $itemIds;
    }

    /**
     * @param string $itemType
     * @param array $batchItemIds
     *
     * @return void
     */
    protected function deleteObsoleteTouchRecords($itemType, array $batchItemIds)
    {
        if (!$batchItemIds) {
            return;
        }
        $touchIds = $this->queryObsoleteTouchIds($itemType, $batchItemIds)->find()->getData();

        if (!$touchIds) {
            return;
        }

        $this->getSpyTouchQuery()->filterByIdTouch_In($touchIds)->delete();
    }

    /**
     * @param string $itemType
     * @param array $batchItemIds
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    protected function queryObsoleteTouchIds($itemType, array $batchItemIds)
    {
        return $this->getSpyTouchQuery()
            ->filterByItemEvent(SpyTouchTableMap::COL_ITEM_EVENT_DELETED)
            ->filterByItemType($itemType)
            ->filterByItemId_In($batchItemIds)
            ->leftJoinTouchSearch()
            ->leftJoinTouchStorage()
            ->addAnd(SpyTouchStorageTableMap::COL_ID_TOUCH_STORAGE, null, Criteria::ISNULL)
            ->addAnd(SpyTouchSearchTableMap::COL_ID_TOUCH_SEARCH, null, Criteria::ISNULL)
            ->withColumn(SpyTouchTableMap::COL_ID_TOUCH, static::ID_TOUCH)
            ->select([static::ID_TOUCH]);
    }

    /**
     * @return \Orm\Zed\Touch\Persistence\SpyTouchQuery
     */
    protected function getSpyTouchQuery()
    {
        return SpyTouchQuery::create();
    }

    /**
     * @return bool
     */
    protected function isTouchDeleteCleanupActive()
    {
        return Config::get(CollectorConstants::TOUCH_DELETE_CLEANUP_ACTIVE, false);
    }
}
