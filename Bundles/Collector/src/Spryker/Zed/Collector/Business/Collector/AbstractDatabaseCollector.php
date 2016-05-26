<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Shared\Library\BatchIterator\CountableIteratorInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractDatabaseCollector extends AbstractCollector implements DatabaseCollectorInterface
{

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \Spryker\Shared\Library\BatchIterator\CountableIteratorInterface
     */
    public function collectDataFromDatabase(
        SpyTouchQuery $touchQuery,
        LocaleTransfer $locale
    ) {
        $this->prepareCollectorScope($touchQuery, $locale);
        $batchCollection = $this->generateBatchIterator();
        return $batchCollection;
    }

    /**
     * @param \Spryker\Shared\Library\BatchIterator\CountableIteratorInterface $batchCollection
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
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
        WriterInterface $storeWriter,
        LocaleTransfer $locale,
        OutputInterface $output
    ) {
        $this->displayProgressWhileCountingBatchCollectionSize($output);
        $totalCount = $batchCollection->count();
        $batchResult->setTotalCount($totalCount);

        $progressBar = $this->generateProgressBar($output, $totalCount);
        $progressBar->start();
        $progressBar->advance(0);

        foreach ($batchCollection as $batch) {
            $batchSize = count($batch);
            $progressBar->advance($batchSize);

            $touchUpdaterSet = new TouchUpdaterSet(CollectorConfig::COLLECTOR_TOUCH_ID);
            $collectedData = $this->collectData(
                $batch,
                $locale,
                $touchUpdaterSet
            );
            $collectedDataCount = count($collectedData);

            $touchUpdater->bulkUpdate(
                $touchUpdaterSet,
                $locale->getIdLocale(),
                $this->touchQueryContainer->getConnection()
            );
            $storeWriter->write($collectedData, $this->collectResourceType());

            $batchResult->increaseProcessedCount($collectedDataCount);
        }

        $progressBar->finish();

        $output->writeln('');
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
            $entityCollection = $this->getTouchCollectionToDelete(
                $offset,
                $itemType
            );
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
                    $storeWriter->delete($keysToDelete);
                }
            }
        }

        return $deletedCount;
    }

}
