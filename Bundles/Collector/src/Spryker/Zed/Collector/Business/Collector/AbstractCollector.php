<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use PDO;
use Propel\Runtime\Formatter\StatementFormatter;
use Spryker\Shared\Gui\ProgressBar\ProgressBarBuilder;
use Spryker\Shared\KeyBuilder\KeyBuilderTrait;
use Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Persistence\Collector\AbstractCollectorQuery;
use Spryker\Zed\Kernel\Locator;
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
     * @var \Spryker\Zed\Collector\Persistence\Collector\AbstractCollectorQuery
     */
    protected $queryBuilder;

    /**
     * @var \Generated\Shared\Transfer\LocaleTransfer
     */
    protected $locale;

    /**
     * @var \Generated\Shared\Transfer\StoreTransfer
     */
    protected $currentStoreBuffer;

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
     * @return \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface
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
     * @param array $collectItemData
     *
     * @return bool True if the item can be exported; false if the item should be removed when stored.
     */
    protected function isStorable(array $collectItemData)
    {
        return true;
    }

    /**
     * @return \Generated\Shared\Transfer\StoreTransfer
     */
    protected function getCurrentStore()
    {
        if ($this->currentStoreBuffer === null) {
            // Deprecated: inject StoreFacade through constructor
            $this->currentStoreBuffer = Locator::getInstance()->store()->facade()->getCurrentStore();
        }

        return $this->currentStoreBuffer;
    }

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
     * @param \Spryker\Zed\Collector\Persistence\Collector\AbstractCollectorQuery $queryBuilder
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
            if (!$this->isStorable($collectedItemData)) {
                continue;
            }

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
     * @param array $collectedSet
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return string[]
     */
    protected function collectExpiredData(array $collectedSet, LocaleTransfer $locale)
    {
        $expiredData = [];

        foreach ($collectedSet as $index => $collectedItemData) {
            if ($this->isStorable($collectedItemData)) {
                continue;
            }

            $touchKey = $this->collectKey(
                $collectedItemData[CollectorConfig::COLLECTOR_RESOURCE_ID],
                $locale->getLocaleName(),
                $collectedItemData
            );
            $expiredData[$touchKey] = $collectedItemData;
        }

        return $expiredData;
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
     * @param string $itemType
     * @param int $offset
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch[]
     */
    protected function getTouchCollectionToDelete($itemType, $offset = 0)
    {
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteStorageAndSearch($itemType, $this->getCurrentStore()->getIdStore(), $this->locale->getIdLocale());
        $deleteQuery
            ->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID)
            ->withColumn('search.key', CollectorConfig::COLLECTOR_SEARCH_KEY)
            ->withColumn('storage.key', CollectorConfig::COLLECTOR_STORAGE_KEY)
            ->setOffset($offset)
            ->setLimit($this->chunkSize)
            ->setFormatter(StatementFormatter::class);

        $params = [];
        $sql = $deleteQuery->createSelectSql($params);
        $params = $this->getTouchQueryParameters($deleteQuery);
        $statement = $this->touchQueryContainer->getConnection()->prepare($sql);

        $sqlParams = [];
        $step = 1;
        foreach ($params as $key => $value) {
            $sqlParams['p' . $step] = $value;
            $statement->bindParam(':p' . $step, $value);
            $step++;
        }

        $statement->execute($sqlParams);

        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * @param array $entityCollection
     * @param string $touchKeyColumnName
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet $touchUpdaterSet
     *
     * @return array
     */
    protected function getKeysToDeleteAndUpdateTouchUpdaterSet(
        array $entityCollection,
        $touchKeyColumnName,
        TouchUpdaterSet $touchUpdaterSet
    ) {
        $keysToDelete = [];

        foreach ($entityCollection as $entityData) {
            $key = $entityData[$touchKeyColumnName];

            if (trim($key) !== '') {
                $keysToDelete[$key] = true;
                $this->appendTouchUpdaterSetItem(
                    $touchUpdaterSet,
                    $key,
                    $entityData[CollectorConfig::COLLECTOR_TOUCH_ID],
                    $entityData
                );
            }
        }

        return $keysToDelete;
    }

    /**
     * @throws \Spryker\Zed\Collector\Business\Exporter\Exception\DependencyException
     *
     * @return void
     */
    protected function validateDependencies()
    {
        if (!($this->touchQueryContainer instanceof TouchQueryContainerInterface)) {
            throw new DependencyException(sprintf(
                'touchQueryContainer does not implement TouchQueryContainerInterface in %s',
                static::class
            ));
        }

        if (!($this->queryBuilder instanceof AbstractCollectorQuery)) {
            throw new DependencyException(sprintf(
                'queryBuilder does not implement AbstractCollectorQuery in %s',
                static::class
            ));
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
            if ($value instanceof DateTime) {
                $value = $value->format(DateTime::ATOM);
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

        $progressBar->setFormat(" * %barTitle%\x0D ");
        $progressBar->start();
        $progressBar->advance();
        $progressBar->finish();
    }
}
