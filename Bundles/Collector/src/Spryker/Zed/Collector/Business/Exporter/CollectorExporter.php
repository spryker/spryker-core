<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Base\SpyTouchQuery;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\Formatter\SimpleArrayFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Collector\Business\Exporter\Exception\BatchResultException;
use Spryker\Zed\Collector\Business\Exporter\Exception\UndefinedCollectorTypesException;
use Spryker\Zed\Collector\Business\Exporter\Writer\Storage\TouchUpdaterSet;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CollectorExporter
{

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $touchQueryContainer;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\ExporterInterface
     */
    protected $exporter;

    /**
     * @var \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface
     */
    protected $localeFacade;

    /**
     * @var array
     */
    protected $availableCollectorTypes;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface
     */
    protected $touchUpdater;

    /**
     * @var int
     */
    protected $chunkSize = 1000;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $touchQueryContainer
     * @param \Spryker\Zed\Collector\Dependency\Facade\CollectorToLocaleInterface $localeFacade
     * @param \Spryker\Zed\Collector\Business\Exporter\ExporterInterface $exporter
     * @param array $availableCollectorTypes
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     */
    public function __construct(
        TouchQueryContainerInterface $touchQueryContainer,
        CollectorToLocaleInterface $localeFacade,
        ExporterInterface $exporter,
        array $availableCollectorTypes,
        TouchUpdaterInterface $touchUpdater
    ) {
        $this->touchQueryContainer = $touchQueryContainer;
        $this->localeFacade = $localeFacade;
        $this->exporter = $exporter;
        $this->availableCollectorTypes = $availableCollectorTypes;
        $this->touchUpdater = $touchUpdater;
    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @return bool
     * @throws \Exception
     */
    public function cleanupTouchTablesByLocale(LocaleTransfer $locale)
    {
        $availableTypes = $this->getAvailableCollectorTypes();

        // now that we have deleted from front-end storage, we should also clear the touch tables in Zed
        $touchUpdaterSet = new TouchUpdaterSet(CollectorConfig::COLLECTOR_TOUCH_ID);
        $this->touchQueryContainer->getConnection()->beginTransaction();
        foreach ($availableTypes as $type) {
            try {
                $entityCollection = $this->getTouchCollectionToDelete($type);
                $batchCount = count($entityCollection);

                if ($batchCount > 0) {

                    $this->touchUpdater->bulkDelete(
                        $touchUpdaterSet,
                        $locale->getIdLocale(),
                        $this->touchQueryContainer->getConnection()
                    );

                    $this->bulkDeleteTouchEntities($entityCollection);
                }
            }
            catch (\Exception $exception) {
                $this->touchQueryContainer->getConnection()->rollBack();
                throw $exception;
            }
        }
        $this->touchQueryContainer->getConnection()->commit();

        return true;

    }

    /**
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return array
     */
    public function exportStorageByLocale(LocaleTransfer $locale, OutputInterface $output)
    {
        $results = [];
        $types = array_keys($this->exporter->getCollectorPlugins());
        $availableTypes = $this->getAvailableCollectorTypes();

        $output->writeln('');
        $output->writeln(sprintf('<fg=yellow>Locale:</fg=yellow> <fg=white>%s</fg=white>', $locale->getLocaleName()));
        $output->writeln('<fg=yellow>-------------</fg=yellow>');

        foreach ($availableTypes as $type) {
            if (!in_array($type, $types)) {
                $output->write('<fg=yellow> * </fg=yellow><fg=green>' . $type . '</fg=green> ');
                $output->write('<fg=white>N/A</fg=white>');
                $output->writeln('');
                continue;
            }

            $result = $this->exporter->exportByType($type, $locale, $output);

            $this->handleResult($result);

            if ($result instanceof BatchResultInterface) {
                if ($this->nothingWasProcessed($result)) {
                    continue;
                }
                $results[$type] = $result;
            }
        }

        return $results;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouch[] $entityCollection
     *
     * @return void
     */
    protected function bulkDeleteTouchEntities(array $entityCollection)
    {
        foreach ($entityCollection as $entity) {
            $idList[] = $entity[CollectorConfig::COLLECTOR_TOUCH_ID];
        }

        if (empty($idList)) {
            return;
        }

        $idListSql = rtrim(implode(',', $idList), ',');

        $sql = sprintf(
            'DELETE FROM %s WHERE %s IN (%s)',
            SpyTouchTableMap::TABLE_NAME,
            SpyTouchTableMap::COL_ID_TOUCH,
            $idListSql
        );
        $this->touchQueryContainer->getConnection()->exec($sql);
    }

    /**
     * @param string $itemType
     *
     * @return \Orm\Zed\Touch\Persistence\SpyTouch[]
     */
    protected function getTouchCollectionToDelete($itemType)
    {
        $deleteQuery = $this->touchQueryContainer->queryTouchDeleteStorageAndSearch($itemType);
        $deleteQuery
            ->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID)
            ->withColumn('search.key', CollectorConfig::COLLECTOR_SEARCH_KEY)
            ->withColumn('storage.key', CollectorConfig::COLLECTOR_STORAGE_KEY)
            ->setLimit($this->chunkSize)
            ->setFormatter(\Propel\Runtime\Formatter\StatementFormatter::class);

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
        };

        $statement->execute($sqlParams);

        return $statement->fetchAll(\PDO::FETCH_ASSOC);
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
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return array
     */
    public function exportStorage(OutputInterface $output)
    {
        $storeCollection = Store::getInstance()->getAllowedStores();

        $results = [];

        foreach ($storeCollection as $storeName) {
            $output->writeln('');
            $output->writeln('<fg=yellow>----------------------------------------</fg=yellow>');
            $output->writeln(sprintf(
                '<fg=yellow>Exporting Store:</fg=yellow> <fg=white>%s</fg=white>',
                $storeName
            ));
            $output->writeln('');

            $localeCollection = Store::getInstance()->getLocalesPerStore($storeName);
            foreach ($localeCollection as $locale => $localeCode) {
                $localeTransfer = $this->localeFacade->getLocale($localeCode);
                $results[$storeName . '@' . $localeCode] = $this->exportStorageByLocale($localeTransfer, $output);
            }
        }

        return $results;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     *
     * @return bool
     */
    protected function nothingWasProcessed(BatchResultInterface $result)
    {
        return $result->getProcessedCount() === 0;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     *
     * @return void
     */
    protected function handleResult(BatchResultInterface $result)
    {
        if ($result->isFailed()) {
            throw new BatchResultException(
                sprintf(
                    'Processed %d from %d for locale %s, where %d were deleted and %d failed.',
                    $result->getProcessedCount(),
                    $result->getTotalCount(),
                    $result->getProcessedLocale(),
                    $result->getDeletedCount(),
                    $result->getFailedCount()
                )
            );
        }
    }

    /**
     * @return array
     */
    public function getAllCollectorTypes()
    {
        return $this->touchQueryContainer
            ->queryExportTypes()
            ->setFormatter(new SimpleArrayFormatter())
            ->find()
            ->toArray();
    }

    /**
     * @return array
     */
    public function getEnabledCollectorTypes()
    {
        return array_keys($this->exporter->getCollectorPlugins());
    }

    /**
     * @return array
     */
    protected function getAvailableCollectorTypes()
    {
        if (empty($this->availableCollectorTypes)) {
            throw new UndefinedCollectorTypesException();
        }

        $availableTypes = $this->touchQueryContainer->queryExportTypes()->find();
        if (empty($availableTypes)) {
            $availableTypes = $this->availableCollectorTypes;
        }

        return $availableTypes;
    }

}
