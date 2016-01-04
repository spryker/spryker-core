ds<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Plugin;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderTrait;
use SprykerFeature\Zed\Collector\Business\Exporter\Exception\DependencyException;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Business\Model\CountableIteratorInterface;
use SprykerFeature\Zed\Collector\CollectorConfig;
use SprykerFeature\Zed\Collector\Persistence\Exporter\AbstractCollectorQuery;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCollectorPlugin
{

    use KeyBuilderTrait;

    /**
     * @var int
     */
    protected $chunkSize = 1000;

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var AbstractCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @var LocaleTransfer
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
     * @return CountableIteratorInterface
     */
    abstract protected function generateBatchIterator();

    /**
     * @param SpyTouchQuery $touchQuery
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    abstract protected function prepareCollectorScope(SpyTouchQuery $touchQuery, LocaleTransfer $locale);

    /**
     * @param TouchQueryContainerInterface $touchQueryContainer
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

    /**
     * @param AbstractCollectorQuery $queryBuilder
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
     */
    public function setChunkSize($chunkSize)
    {
        $this->chunkSize = $chunkSize;
    }

    /**
     * @param array $collectedSet
     * @param LocaleTransfer $locale
     * @param TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function collectData(array $collectedSet, LocaleTransfer $locale, TouchUpdaterSet $touchUpdaterSet)
    {
        $setToExport = [];

        foreach ($collectedSet as $index => $collectedItemData) {
            $touchKey = $this->collectKey($collectedItemData[CollectorConfig::COLLECTOR_RESOURCE_ID], $locale->getLocaleName(), $collectedItemData);
            $setToExport[$touchKey] = $this->processCollectedItem($touchKey, $collectedItemData, $touchUpdaterSet);
        }

        return $setToExport;
    }

    /**
     * @param $data
     * @param $localeName
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
     * @param TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function processCollectedItem($touchKey, array $collectItemData, TouchUpdaterSet $touchUpdaterSet)
    {
        $touchUpdaterSet->add($touchKey, $collectItemData[CollectorConfig::COLLECTOR_TOUCH_ID], [
            CollectorConfig::COLLECTOR_STORAGE_KEY => $this->getCollectorStorageKeyId($collectItemData),
            CollectorConfig::COLLECTOR_SEARCH_KEY => $this->getCollectorSearchKeyId($collectItemData),
        ]);

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
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function run(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, OutputInterface $output)
    {
        $this->validateDependencies();

        $itemType = $baseQuery->get(SpyTouchTableMap::COL_ITEM_TYPE);

        $this->runDeletion($locale, $result, $dataWriter, $touchUpdater, $itemType, $output);
        $this->runInsertion($baseQuery, $locale, $result, $dataWriter, $touchUpdater, $output);
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $batchResult
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    protected function runInsertion(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $batchResult,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, OutputInterface $output)
    {
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
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function postRun(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater)
    {
        $this->validateDependencies();
    }

    /**
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $batchResult
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     * @param string $itemType
     */
    protected function runDeletion(LocaleTransfer $locale, BatchResultInterface $batchResult,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, $itemType, OutputInterface $output)
    {
        $this->delete($itemType, $dataWriter, $touchUpdater, $locale);

        $deletedCount = $this->flushDeletedTouchStorageAndSearchKeys($itemType);
        $batchResult->setDeletedCount($deletedCount);
    }

    /**
     * @param string $itemType
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     * @param LocaleTransfer $locale
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function delete($itemType, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater,
        LocaleTransfer $locale)
    {
        $touchUpdaterSet = new TouchUpdaterSet(CollectorConfig::COLLECTOR_TOUCH_ID);
        $keysToDelete = [];
        $batchCount = 1;
        $offset = 0;
        $deletedCount = 0;

        $this->touchQueryContainer->getConnection()->beginTransaction();
        try {
            while ($batchCount > 0) {
                $deleteQuery = $this->touchQueryContainer->queryTouchDeleteOnlyByItemType($itemType);
                $deleteQuery
                    ->setOffset($offset)
                    ->setLimit($this->chunkSize);

                $entityCollection = $deleteQuery->find();
                $batchCount = count($entityCollection);

                if ($batchCount > 0) {
                    $deletedCount += $batchCount;
                    $offset += $this->chunkSize;

                    foreach ($entityCollection as $touchEntity) {
                        $entityData = $touchEntity->toArray();
                        $key = $entityData[$touchUpdater->getTouchKeyColumnName()];

                        if (trim($key) !== '') {
                            $keysToDelete[$key] = true;
                            $touchUpdaterSet->add($key, $touchEntity->getIdTouch(), [
                                CollectorConfig::COLLECTOR_STORAGE_KEY => $this->getCollectorStorageKeyId($entityData),
                                CollectorConfig::COLLECTOR_SEARCH_KEY => $this->getCollectorSearchKeyId($entityData),
                            ]);
                        }
                    }

                    if (!empty($keysToDelete)) {
                        $touchUpdater->deleteMulti($touchUpdaterSet, $locale->getIdLocale(), $this->touchQueryContainer->getConnection());
                        $dataWriter->delete($keysToDelete);
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        $this->touchQueryContainer->getConnection()->commit();
    }

    /**
     * @param $itemType
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function flushDeletedTouchStorageAndSearchKeys($itemType)
    {
        $idList = [];
        $batchCount = 1;
        $offset = 0;
        $deletedCount = 0;

        $this->touchQueryContainer->getConnection()->beginTransaction();
        try {
            while ($batchCount > 0) {
                $deleteQuery = $this->touchQueryContainer
                    ->queryTouchDeleteStorageAndSearch($itemType);

                $deleteQuery
                    ->setOffset($offset)
                    ->setLimit($this->chunkSize);

                $entityCollection = $deleteQuery->find();
                $batchCount = count($entityCollection);

                if ($batchCount > 0) {
                    $deletedCount += $batchCount;
                    $offset += $this->chunkSize;

                    foreach ($entityCollection as $entity) {
                        $idList[] = $entity->getIdTouch();
                    }

                    if (!empty($idList)) {
                        $sql = 'DELETE FROM %s WHERE %s IN (%s)';
                        $sql = sprintf($sql,
                            SpyTouchTableMap::TABLE_NAME,
                            SpyTouchTableMap::COL_ID_TOUCH,
                            rtrim(
                                implode(',', $idList),
                                ','
                            )
                        );

                        $this->touchQueryContainer->getConnection()->exec($sql);
                    }
                }
            }
        } catch (\Exception $exception) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $exception;
        }

        $this->touchQueryContainer->getConnection()->commit();

        return $deletedCount;
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
     * @param SpyTouchQuery $baseQuery
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

    protected function setupProgressBar()
    {
        ProgressBar::setFormatDefinition('normal', ' <fg=yellow>*</fg=yellow> <fg=green>%collectorType%</fg=green> <fg=yellow>%percent%% (%current%/%max%) %elapsed:6s%</fg=yellow>');
        ProgressBar::setFormatDefinition('normal_nomax', ' <fg=yellow>*</fg=yellow> <fg=green>%collectorType%</fg=green> <fg=yellow>(%max%)</fg=yellow>');

        ProgressBar::setFormatDefinition('verbose', " <fg=yellow>*</fg=yellow> <fg=green>%collectorType%</fg=green> <fg=yellow>[%bar%] %percent%% (%current%/%max%) %elapsed:6s% %memory:6s%</fg=yellow>\x0D");
        ProgressBar::setFormatDefinition('verbose_nomax', ' <fg=yellow>*</fg=yellow> <fg=green>%collectorType%</fg=green> <fg=yellow>(%max%)</fg=yellow>');

        ProgressBar::setFormatDefinition('very_verbose', " <fg=yellow>*</fg=yellow> <fg=green>%collectorType:-20s%</fg=green> %bar% <fg=yellow>%percent%% (%current%/%max%) %memory% (%elapsed%/%remaining%)</fg=yellow>\x0D");
        ProgressBar::setFormatDefinition('very_verbose_nomax', ' <fg=yellow>*</fg=yellow> <fg=green>%collectorType%</fg=green> <fg=yellow>(%max%)</fg=yellow>');

        ProgressBar::setFormatDefinition('debug', " <fg=yellow>*</fg=yellow> <fg=green>%collectorType:-20s%</fg=green> %bar% <fg=yellow>%percent:20s%% [%current%/%max%] Memory: %memory%, Elapsed: %elapsed%, Remaining: %remaining%</fg=yellow>\x0D");
        ProgressBar::setFormatDefinition('debug_nomax', ' <fg=yellow>*</fg=yellow> <fg=green>%collectorType%</fg=green> <fg=yellow>(%max%)</fg=yellow>');
    }

    /**
     * @param OutputInterface $output
     * @param int $count
     *
     * @return ProgressBar
     */
    protected function generateProgressBar(OutputInterface $output, $count)
    {
        $this->setupProgressBar();

        $progressBar = new ProgressBar($output, $count);
        $progressBar->setMessage($this->collectResourceType(), 'collectorType');
        $progressBar->setBarWidth(20);

        if ($output->getVerbosity() > OutputInterface::VERBOSITY_VERBOSE) {
            $progressBar->setBarCharacter($done = "\033[32m●\033[0m");
            $progressBar->setEmptyBarCharacter($empty = "\033[31m●\033[0m");
            $progressBar->setProgressCharacter($progress = "\033[32m►\033[0m");
            $progressBar->setBarWidth(50);
        }

        return $progressBar;
    }

    /**
     * @param OutputInterface $output
     *
     * @return void
     */
    protected function displayProgressWhileCountingBatchCollectionSize(OutputInterface $output)
    {
        //display progress while counting in real progress bar
        $progressBar = new ProgressBar($output, 1);
        $progressBar->setMessage($this->collectResourceType(), 'collectorType');
        $progressBar->setFormat(" * %collectorType%\x0D ");
        $progressBar->start();
        $progressBar->advance();
        $progressBar->finish();
    }

}
