<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Everon\Component\CriteriaBuilder\CriteriaBuilderInterface;
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
use SprykerFeature\Zed\Distributor\Business\Distributor\BatchIteratorInterface;

abstract class AbstractPdoCollectorPlugin
{

    use KeyBuilderTrait;

    const COLLECTOR_TOUCH_ID = 'collector_touch_id';
    const COLLECTOR_RESOURCE_ID = 'collector_resource_id';

    /**
     * @var int
     */
    protected $chunkSize = 1000;

    /**
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var CriteriaBuilderInterface
     */
    protected $criteriaBuilder;

    /**
     * @var AbstractPdoCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @var UpdaterInterface
     */
    protected $exportUpdater;

    /**
     * @param array $collectItemData
     *
     * @return array
     */
    abstract protected function collectItem(array $collectItemData);

    /**
     * @return string
     */
    abstract protected function collectResourceType();

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
            $categoryKey = $this->generateKey($collectedItemData[static::COLLECTOR_RESOURCE_ID], $locale->getLocaleName());
            $setToExport[$categoryKey] = $this->processCollectedItem($categoryKey, $collectedItemData, $touchUpdaterSet);
        }

        return $setToExport;
    }

    /**
     * @param string $categoryKey
     * @param array $collectItemData
     * @param TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function processCollectedItem($categoryKey, array $collectItemData, TouchUpdaterSet $touchUpdaterSet)
    {
        $touchUpdaterSet->add($categoryKey, $collectItemData[static::COLLECTOR_TOUCH_ID]);

        return $this->collectItem($collectItemData);
    }

    /**
     * @param TouchQueryContainerInterface $touchQueryContainer
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

    /**
     * @param CriteriaBuilderInterface $criteriaBuilder
     */
    public function setCriteriaBuilder(CriteriaBuilderInterface $criteriaBuilder)
    {
        $this->criteriaBuilder = $criteriaBuilder;
    }

    /**
     * @param AbstractPdoCollectorQuery $queryBuilder
     */
    public function setQueryBuilder(AbstractPdoCollectorQuery $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param UpdaterInterface $exportUpdater
     */
    public function setExportUpdater(UpdaterInterface $exportUpdater)
    {
        $this->exportUpdater = $exportUpdater;
    }

    /**
     * @return BatchIteratorInterface
     */
    protected function generateBatchIterator()
    {
        return new PdoBatchIterator(
            $this->criteriaBuilder,
            $this->touchQueryContainer->getConnection(),
            $this->chunkSize
        );
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
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function run(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater
    ) {
        $this->validateDependencies();

        $itemType = $baseQuery->get(SpyTouchTableMap::COL_ITEM_TYPE);

        $this->runDeletion($locale, $result, $dataWriter, $touchUpdater, $itemType);
        $this->runInsertion($baseQuery, $locale, $result, $dataWriter, $touchUpdater);
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $batchResult
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    protected function runInsertion(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $batchResult,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater
    ) {
        $this->prepareCollector($baseQuery, $locale);

        $batchCollection = $this->generateBatchIterator();
        foreach ($batchCollection as $batch) {
            $touchUpdaterSet = new TouchUpdaterSet(self::COLLECTOR_TOUCH_ID);
            $collectedData = $this->collectData($batch, $locale, $touchUpdaterSet);
            $collectedDataCount = count($collectedData);

            $touchUpdater->updateMulti($touchUpdaterSet, $locale->getIdLocale());

            $dataWriter->write($collectedData, $this->collectResourceType());

            $batchResult->increaseProcessedCount($collectedDataCount);

            $batchResult->setTotalCount(
                $batchResult->getTotalCount() + $collectedDataCount
            );
        }
    }

    /**
     * @param SpyTouchQuery $touchQuery
     * @param LocaleTransfer $locale
     *
     * @return void
     */
    protected function prepareCollector(SpyTouchQuery $touchQuery, LocaleTransfer $locale)
    {
        $touchParameters = $this->getTouchQueryParameters($touchQuery);
        $this->criteriaBuilder
            ->setExtraParameterCollection($touchParameters);

        $this->queryBuilder
            ->setCriteriaBuilder($this->criteriaBuilder)
            ->setLocale($locale)
            ->prepareQuery();
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function postRun(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater
    ) {
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
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, $itemType
    ) {
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
        LocaleTransfer $locale
    ) {
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
            throw new DependencyException(sprintf(
                'touchQueryContainer does not implement TouchQueryContainerInterface in %s', get_class($this))
            );
        }

        if (!($this->criteriaBuilder instanceof CriteriaBuilderInterface)) {
            throw new DependencyException(sprintf(
                'criteriaBuilder does not implement CriteriaBuilder\CriteriaBuilderInterface in %s', get_class($this))
            );
        }

        if (!($this->queryBuilder instanceof AbstractPdoCollectorQuery)) {
            throw new DependencyException(sprintf(
                'queryBuilder does not implement AbstractPdoCollectorQuery in %s', get_class($this))
            );
        }

        if (!($this->exportUpdater instanceof UpdaterInterface)) {
            throw new DependencyException(sprintf(
                'exportUpdater does not implement UpdaterInterface in %s', get_class($this))
            );
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
