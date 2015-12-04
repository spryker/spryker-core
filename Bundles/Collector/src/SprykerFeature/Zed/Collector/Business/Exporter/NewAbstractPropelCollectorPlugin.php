<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainerInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Exception\DependencyException;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\KeyValue\TouchUpdaterSet;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Distributor\Business\Distributor\BatchIteratorInterface;

abstract class NewAbstractPropelCollectorPlugin
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
     *
     * @return string
     */
    abstract protected function createQuery(SpyTouchQuery $baseQuery, LocaleTransfer $locale);

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
     * @param TouchQueryContainerInterface $touchQueryContainer
     */
    public function setTouchQueryContainer(TouchQueryContainerInterface $touchQueryContainer)
    {
        $this->touchQueryContainer = $touchQueryContainer;
    }

    /**
     * @param string $baseQuerySql
     *
     * @return BatchIteratorInterface
     */
    protected function getBatchIterator($baseQuerySql)
    {
        return new RawBatchIterator($baseQuerySql, $this->chunkSize);
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
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function run(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater
    ) {
        $itemType = $baseQuery->get(SpyTouchTableMap::COL_ITEM_TYPE);

        if (!($this->touchQueryContainer instanceof TouchQueryContainerInterface)) {
            throw new DependencyException(sprintf(
                'touchQueryContainer does not implement TouchQueryContainerInterface in %s', get_class($this))
            );
        }

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
        $touchQuerySql = $this->createQuery($baseQuery, $locale);

        $idRoot = 1;
        $idLocale = 46;
        $touchEvent = 0;
        $touchItemType = 'categorynode';
        $touchedWhen = '2015-12-03 19:26:53';

        $sql = sprintf($touchQuerySql, $idRoot, self::TOUCH_EXPORTER_ID, $idLocale, $idLocale, $touchEvent, $touchedWhen, $touchItemType);

        $conn = $this->touchQueryContainer->getConnection();
        $st = $conn->prepare($sql);

        $st->execute([

        ]);

        $results = $st->fetchAll(\PDO::FETCH_ASSOC);

        dump($results);
        die;

        $batchCollection = $this->getBatchIterator($touchQuerySql);

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
     * @param SpyTouchQuery $baseQuery
     * @param LocaleTransfer $locale
     * @param BatchResultInterface $result
     * @param WriterInterface $dataWriter
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function postRun(SpyTouchQuery $baseQuery, LocaleTransfer $locale, BatchResultInterface $result,
        WriterInterface $dataWriter, TouchUpdaterInterface $touchUpdater
    ) {
    }

}
