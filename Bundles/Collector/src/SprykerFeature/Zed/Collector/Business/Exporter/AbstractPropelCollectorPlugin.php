<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Exception\DependencyException;
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
     * @var TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

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
    ) {
        $itemType = $baseQuery->get(SpyTouchTableMap::COL_ITEM_TYPE);

        if (null === $this->touchQueryContainer) {
            throw new DependencyException(sprintf('touchQueryContainer does not exist in %s', get_class($this)));
        }

        $this->runDeletion($locale, $result, $dataWriter, $touchUpdater, $itemType);
        $this->runInsertion($baseQuery, $locale, $result, $dataWriter, $touchUpdater);
    }

    /**
     * @param TouchQueryContainerInterface $touchQueryContainer
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

    /**
     * @param string $itemType
     *
     * @return int
     */
    public function removeDeletedRows($itemType)
    {
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteStorageAndSearch($itemType);
        $entityCollection = $deleteQuery->find();
        $deletedCount = 0;
        foreach ($entityCollection as $entity) {
            $entity->delete();
            $deletedCount++;
        }

        return $deletedCount;
    }

    /**
     * @param string $itemType
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     * @param LocaleTransfer $locale
     */
    public function delete($itemType, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, LocaleTransfer $locale)
    {
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteOnlyByItemType($itemType);
        $touchEntities = $deleteQuery->find();

        foreach ($touchEntities as $touchEntity) {
            $keyEntity = $touchUpdater->getKeyById($touchEntity->getIdTouch(), $locale);
            if (!empty($keyEntity)) {
                $key = $keyEntity->getKey();
                $dataWriter->delete([$key]);
                $keyEntity->delete();
            }
        }
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
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
     * @param ModelCriteria $baseQuery
     *
     * @return BatchIterator
     */
    protected function getBatchIterator(ModelCriteria $baseQuery)
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
     * @param BatchResultInterface $batchResult
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     * @param string $itemType
     */
    protected function runDeletion(LocaleTransfer $locale, BatchResultInterface $batchResult, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater, $itemType)
    {
        $this->delete($itemType, $dataWriter, $touchUpdater, $locale);

        $deletedCount = $this->removeDeletedRows($itemType);
        $batchResult->setDeletedCount($deletedCount);
    }

    /**
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $batchResult
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    protected function runInsertion(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $batchResult, WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater)
    {
        $touchQuery = $this->createQuery($baseQuery, $locale);

        $batchCollection = $this->getBatchIterator($touchQuery);

        $totalCount = $batchResult->getTotalCount();
        foreach ($batchCollection as $batch) {
            $touchUpdaterSet = new TouchUpdaterSet();
            $collectedData = $this->processData($batch, $locale, $touchUpdaterSet);
            $collectedDataCount = count($collectedData);

            $touchUpdater->updateMulti($touchUpdaterSet, $locale->getIdLocale());

            $dataWriter->write($collectedData, $this->getTouchItemType());

            $batchResult->increaseProcessedCount($collectedDataCount);
            $totalCount += $collectedDataCount;
        }

        $batchResult->setTotalCount($totalCount);
    }

}
