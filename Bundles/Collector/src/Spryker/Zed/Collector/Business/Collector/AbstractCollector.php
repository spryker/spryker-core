<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Shared\Collector\Code\KeyBuilder\KeyBuilderTrait;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Business\Model\ProgressBarBuilder;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Persistence\Exporter\AbstractCollectorQuery;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCollector
{

    use KeyBuilderTrait;

    /**
     * @var int
     */
    protected $chunkSize = 1000;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Collector\Persistence\Exporter\AbstractCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @param string $touchKey
     * @param array $collectItemData
     *
     * @return array
     */
    abstract protected function collectItem($touchKey, array $collectItemData);

    /**
     * @return string
     */
    abstract protected function collectResourceType();

    /**
     * @return \Spryker\Zed\Collector\Business\Model\CountableIteratorInterface
     */
    abstract protected function generateBatchIterator();

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $touchQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return void
     */
    abstract protected function prepareCollectorScope(SpyTouchQuery $touchQuery, LocaleTransfer $locale);

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     *
     * @return void
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Collector\Persistence\Exporter\AbstractCollectorQuery $queryBuilder
     *
     * @return void
     */
    public function setQueryBuilder(AbstractCollectorQuery $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return $this->chunkSize;
    }

    /**
     * @param int $chunkSize
     *
     * @return void
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
    }

    /**
     * @param array $collectedSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function collectData(array $collectedSet, LocaleTransfer $locale, TouchUpdaterSet $touchUpdaterSet)
    {
        $setToExport = [];

        foreach ($collectedSet as $index => $collectedItemData) {
            $touchKey = $this->collectKey(
                $collectedItemData[CollectorConfig::COLLECTOR_RESOURCE_ID],
                $locale->getLocaleName(),
                $collectedItemData
            );
            $setToExport[$touchKey] = $this->processCollectedItem($touchKey, $collectedItemData, $touchUpdaterSet);
        }

        return $setToExport;
    }

    /**
     * @param mixed $data
     * @param string $localeName
     * @param array $collectedItemData
     *
     * @return string
     */
    protected function collectKey($data, $localeName, array $collectedItemData)
    {
        return $this->generateKey($data, $localeName);
    }

    /**
     * @param string $touchKey
     * @param array $collectItemData
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function processCollectedItem($touchKey, array $collectItemData, TouchUpdaterSet $touchUpdaterSet)
    {
        $this->appendTouchUpdaterSetItem(
            $touchUpdaterSet,
            $touchKey,
            $collectItemData[CollectorConfig::COLLECTOR_TOUCH_ID],
            $collectItemData
        );

        return $this->collectItem($touchKey, $collectItemData);
    }

    /**
     * @param array $collectItemData
     *
     * @return int|null
     */
    protected function getCollectorStorageKeyId(array $collectItemData)
    {
        if (!isset($collectItemData[CollectorConfig::COLLECTOR_STORAGE_KEY])) {
            return null;
        }

        return $collectItemData[CollectorConfig::COLLECTOR_STORAGE_KEY];
    }

    /**
     * @param array $collectItemData
     *
     * @return int|null
     */
    protected function getCollectorSearchKeyId(array $collectItemData)
    {
        if (!isset($collectItemData[CollectorConfig::COLLECTOR_SEARCH_KEY])) {
            return null;
        }

        return $collectItemData[CollectorConfig::COLLECTOR_SEARCH_KEY];
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function run(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {

        $this->validateDependencies();

        $itemType = $baseQuery->get(SpyTouchTableMap::COL_ITEM_TYPE);

        $this->runDeletion($locale, $result, $dataWriter, $touchUpdater, $itemType, $output);
        $this->runInsertion($baseQuery, $locale, $result, $dataWriter, $touchUpdater, $output);
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function runInsertion(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $batchResult,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {

        $this->prepareCollectorScope($baseQuery, $locale);

        $batchCollection = $this->generateBatchIterator();
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
            $collectedData = $this->collectData($batch, $locale, $touchUpdaterSet);
            $collectedDataCount = count($collectedData);

            $touchUpdater->updateMulti($touchUpdaterSet, $locale->getIdLocale(), $this->touchQueryContainer->getConnection());
            $dataWriter->write($collectedData, $this->collectResourceType());

            $batchResult->increaseProcessedCount($collectedDataCount);
        }

        $progressBar->finish();

        $output->writeln('');
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     *
     * @return void
     */
    public function postRun(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater
    ) {

        $this->validateDependencies();
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param string $itemType
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function runDeletion(
        LocaleTransfer $locale,
        BatchResultInterface $batchResult,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        $itemType,
        OutputInterface $output
    ) {

        $this->delete($itemType, $dataWriter, $touchUpdater, $locale);
        $deletedCount = $this->pruneTouchStorageAndSearchKeys($itemType);
        $batchResult->setDeletedCount($deletedCount);
    }

    /**
     * @param string $itemType
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function delete(
        $itemType,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        LocaleTransfer $locale
    ) {

        $touchUpdaterSet = new TouchUpdaterSet(CollectorConfig::COLLECTOR_TOUCH_ID);
        $batchCount = 1;
        $offset = 0;
        $deletedCount = 0;

        $this->touchQueryContainer->getConnection()->beginTransaction();
        try {
            while ($batchCount > 0) {
                $entityCollection = $this->getTouchEntitiesToDelete($offset, $itemType);
                $batchCount = count($entityCollection);

                if ($batchCount > 0) {
                    $deletedCount += $batchCount;
                    $offset += $this->chunkSize;

                    $keysToDelete = $this->getKeysToDeleteAndUpdateTouchUpdaterSet(
                        $entityCollection,
                        $touchUpdater->getTouchKeyColumnName(),
                        $touchUpdaterSet
                    );

                    if (!empty($keysToDelete)) {
                        $touchUpdater->deleteMulti(
                            $touchUpdaterSet,
                            $locale->getIdLocale(),
                            $this->touchQueryContainer->getConnection()
                        );
                        $dataWriter->delete($keysToDelete);
                    }
                }
            }
        }
        catch (\Exception $exception) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        $this->touchQueryContainer->getConnection()->commit();
    }


    /**
     * @param int $offset
     * @param string $itemType
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch[]
     */
    protected function getTouchEntitiesToDelete($offset, $itemType)
    {
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteOnlyByItemType($itemType);
        $deleteQuery
            ->setOffset($offset)
            ->setLimit($this->chunkSize);

        return $deleteQuery->find();
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouch[] $entityCollection
     * @param string $touchKeyColumnName
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function getKeysToDeleteAndUpdateTouchUpdaterSet(
        $entityCollection,
        $touchKeyColumnName,
        TouchUpdaterSet $touchUpdaterSet
    ) {
        $keysToDelete = [];

        foreach ($entityCollection as $touchEntity) {
            $entityData = $touchEntity->toArray();
            $key = $entityData[$touchKeyColumnName];

            if (trim($key) !== '') {
                $keysToDelete[$key] = true;
                $this->appendTouchUpdaterSetItem($touchUpdaterSet, $key, $touchEntity->getIdTouch(), $entityData);
            }
        }

        return $keysToDelete;
    }

    /**
     * @param string $itemType
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function pruneTouchStorageAndSearchKeys($itemType)
    {
        $batchCount = 1;
        $offset = 0;
        $deletedCount = 0;

        $this->touchQueryContainer->getConnection()->beginTransaction();
        try {
            while ($batchCount > 0) {
                $entityCollection = $this->getTouchStorageAndSearchDeletedEntities($offset, $itemType);
                $batchCount = count($entityCollection);

                if ($batchCount > 0) {
                    $deletedCount += $batchCount;
                    $offset += $this->chunkSize;

                    $this->bulkDeleteTouchEntities($entityCollection);
                }
            }
        }
        catch (\Exception $exception) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        $this->touchQueryContainer->getConnection()->commit();

        return $deletedCount;
    }

    /**
     * @param int $offset
     * @param string $itemType
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch[]
     */
    protected function getTouchStorageAndSearchDeletedEntities($offset, $itemType)
    {
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteStorageAndSearch($itemType);

        $deleteQuery->setOffset($offset)->setLimit($this->chunkSize);

        return $deleteQuery->find();
    }


    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouch[] $entityCollection
     *
     * @return void
     */
    protected function bulkDeleteTouchEntities(array $entityCollection)
    {
        foreach ($entityCollection as $entity) {
            $idList[] = $entity->getIdTouch();
        }

        if (empty($idList)) {
            return;
        }

        $idListSql = rtrim(implode(',', $idList), ',');
        $sql = sprintf('DELETE FROM %s WHERE %s IN (%s)', SpyTouchTableMap::TABLE_NAME, SpyTouchTableMap::COL_ID_TOUCH, $idListSql);

        $this->touchQueryContainer->getConnection()->exec($sql);
    }

    /**
     * @return void
     */
    protected function validateDependencies()
    {
        if (!($this->touchQueryContainer instanceof TouchQueryContainerInterface)) {
            throw new DependencyException(sprintf('touchQueryContainer does not implement TouchQueryContainerInterface in %s', get_class($this)));
        }

        if (!($this->queryBuilder instanceof AbstractCollectorQuery)) {
            throw new DependencyException(sprintf('queryBuilder does not implement AbstractCollectorQuery in %s', get_class($this)));
        }
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     *
     * @return array
     */
    protected function getTouchQueryParameters(SpyTouchQuery $baseQuery)
    {
        $result = [];
        $baseParameters = $baseQuery->getParams();

        foreach ($baseParameters as $parameter) {
            $key = sprintf('%s.%s', $parameter['table'], $parameter['column']);
            $value = $parameter['value'];
            if ($value instanceof \DateTime) {
                $value = $value->format(\DateTime::ATOM);
            }
            $result[$key] = $value;
        }

        return $result;
    }

    /**
     * @param string $identifier
     *
     * @return string
     */
    protected function buildKey($identifier)
    {
        return $this->collectResourceType() . '.' . $identifier;
    }

    /**
     * @return string
     */
    public function getBundleName()
    {
        return 'resource';
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     * @param string $collectorKey
     * @param int $touchId
     * @param array $data
     *
     * @return void
     */
    protected function appendTouchUpdaterSetItem(TouchUpdaterSet $touchUpdaterSet, $collectorKey, $touchId, array $data)
    {
        $touchUpdaterSet->add($collectorKey, $touchId, [
            CollectorConfig::COLLECTOR_STORAGE_KEY => $this->getCollectorStorageKeyId($data),
            CollectorConfig::COLLECTOR_SEARCH_KEY => $this->getCollectorSearchKeyId($data),
        ]);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param int $count
     *
     * @return \Symfony\Component\Console\Helper\ProgressBar
     */
    protected function generateProgressBar(OutputInterface $output, $count)
    {
        $builder = new ProgressBarBuilder($output, $count, $this->collectResourceType());

        return $builder->build();
    }

    /**
     * Display progress while counting data for real progress bar
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function displayProgressWhileCountingBatchCollectionSize(OutputInterface $output)
    {
        $builder = new ProgressBarBuilder($output, 1, $this->collectResourceType());
        $progressBar = $builder->build();

        $progressBar->setFormat(" * %collectorType%\x0D ");
        $progressBar->start();
        $progressBar->advance();
        $progressBar->finish();
    }

}
