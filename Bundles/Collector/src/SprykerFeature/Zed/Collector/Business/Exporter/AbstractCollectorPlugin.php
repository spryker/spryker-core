ds<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

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
use SprykerFeature\Zed\Collector\Persistence\Exporter\AbstractCollectorQuery;
use SprykerFeature\Zed\Distributor\Business\Distributor\BatchIteratorInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCollectorPlugin
{

    use KeyBuilderTrait;

    const COLLECTOR_TOUCH_ID = 'collector_touch_id';
    const COLLECTOR_RESOURCE_ID = 'collector_resource_id';
    const COLLECTOR_STORAGE_KEY_ID = 'collector_storage_key_id';
    const COLLECTOR_SEARCH_KEY_ID = 'collector_search_key_id';

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
     * @return BatchIteratorInterface
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
    protected function collectData($collectedSet, LocaleTransfer $locale, TouchUpdaterSet $touchUpdaterSet)
    {
        $setToExport = [];

        foreach ($collectedSet as $index => $collectedItemData) {
            $touchKey = $this->generateKey($collectedItemData[static::COLLECTOR_RESOURCE_ID], $locale->getLocaleName());
            $setToExport[$touchKey] = $this->processCollectedItem($touchKey, $collectedItemData, $touchUpdaterSet);
        }

        return $setToExport;
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
        $touchUpdaterSet->add($touchKey, $collectItemData[static::COLLECTOR_TOUCH_ID], [
            static::COLLECTOR_STORAGE_KEY_ID => $this->getCollectorStorageKeyId($collectItemData),
            static::COLLECTOR_SEARCH_KEY_ID => $this->getCollectorSearchKeyId($collectItemData),
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
        if (!isset($collectItemData[static::COLLECTOR_STORAGE_KEY_ID])) {
            return null;
        }

        return $collectItemData[static::COLLECTOR_STORAGE_KEY_ID];
    }

    /**
     * @param array $collectItemData
     *
     * @return int|null
     */
    protected function getCollectorSearchKeyId(array $collectItemData)
    {
        if (!isset($collectItemData[static::COLLECTOR_SEARCH_KEY_ID])) {
            return null;
        }

        return $collectItemData[static::COLLECTOR_SEARCH_KEY_ID];
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
        $progressBar = new ProgressBar($output, $batchCollection->count());
        $progressBar->setFormat('verbose');
        $progressBar->setMessage($this->collectResourceType(), 'collectorType');
        $progressBar->setFormat(" <fg=yellow>*</fg=yellow> <fg=green>%collectorType%</fg=green> <fg=yellow>[%bar%]</fg=yellow> <fg=white>%current%/%max% %elapsed:6s%, %memory:6s%</fg=white>\x0D");

        $progressBar->start();

        foreach ($batchCollection as $batch) {
            $progressBar->advance(0); //show progress bar right away

            $touchUpdaterSet = new TouchUpdaterSet(self::COLLECTOR_TOUCH_ID);
            $collectedData = $this->collectData($batch, $locale, $touchUpdaterSet);
            $collectedDataCount = count($collectedData);

            $touchUpdater->updateMulti($touchUpdaterSet, $locale->getIdLocale(), $this->touchQueryContainer->getConnection());
            $dataWriter->write($collectedData, $this->collectResourceType());

            $batchResult->increaseProcessedCount($collectedDataCount);
            $batchResult->setTotalCount(
                $batchResult->getTotalCount() + $collectedDataCount
            );

            $progressBar->advance($collectedDataCount);
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
     * @param $itemType
     *
     * @throws \Exception
     *
     * @return int
     */
    protected function flushDeletedTouchStorageAndSearchKeys($itemType)
    {
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteStorageAndSearch($itemType);
        $entityCollection = $deleteQuery->find();
        $deletedCount = count($entityCollection);

        $this->touchQueryContainer->getConnection()->beginTransaction();
        try {
            foreach ($entityCollection as $entity) {
                $entity->delete();
            }
        } catch (\Exception $e) {
            $this->touchQueryContainer->getConnection()->rollBack();
            throw $e;
        }

        $this->touchQueryContainer->getConnection()->commit();

        return $deletedCount;
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
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteOnlyByItemType($itemType);
        $touchEntities = $deleteQuery->find();

        foreach ($touchEntities as $touchEntity) {
            $keyEntity = $touchUpdater->getKeyById($touchEntity->getIdTouch(), $locale);
            if (!empty($keyEntity)) {
                $key = $keyEntity->getKey();
                $dataWriter->delete($key);
                $keyEntity->delete();
            }
        }
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

}
