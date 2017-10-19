<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Business\Model\FailedResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface;
use Spryker\Zed\PropelOrm\Business\Model\Formatter\PropelArraySetFormatter;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractExporter implements ExporterInterface
{
    /**
     * @var \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface[]
     */
    protected $collectorPlugins = [];

    /**
     * @var \Spryker\Zed\Collector\Business\Model\FailedResultInterface
     */
    protected $failedResultPrototype;

    /**
     * @var \Spryker\Zed\Collector\Business\Model\BatchResultInterface
     */
    protected $batchResultPrototype;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface
     */
    protected $touchUpdater;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\MarkerInterface
     */
    protected $marker;

    /**
     * @var \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $reader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $writer
     * @param \Spryker\Zed\Collector\Business\Exporter\MarkerInterface $marker
     * @param \Spryker\Zed\Collector\Business\Model\FailedResultInterface $failedResultPrototype
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResultPrototype
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     */
    public function __construct(
        TouchQueryContainerInterface $queryContainer,
        ReaderInterface $reader,
        WriterInterface $writer,
        MarkerInterface $marker,
        FailedResultInterface $failedResultPrototype,
        BatchResultInterface $batchResultPrototype,
        TouchUpdaterInterface $touchUpdater
    ) {
        $this->queryContainer = $queryContainer;
        $this->reader = $reader;
        $this->writer = $writer;
        $this->marker = $marker;
        $this->failedResultPrototype = $failedResultPrototype;
        $this->batchResultPrototype = $batchResultPrototype;
        $this->touchUpdater = $touchUpdater;
    }

    /**
     * @param string $touchItemType
     * @param \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface $plugin
     *
     * @return void
     */
    public function addCollectorPlugin($touchItemType, CollectorPluginInterface $plugin)
    {
        $this->collectorPlugins[$touchItemType] = $plugin;
    }

    /**
     * @return \Spryker\Zed\Collector\Dependency\Plugin\CollectorPluginInterface[]
     */
    public function getCollectorPlugins()
    {
        return $this->collectorPlugins;
    }

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $locale, OutputInterface $output)
    {
        $result = clone $this->batchResultPrototype;
        $result->setProcessedLocale($locale);

        if (!$this->isCollectorRegistered($type)) {
            $this->resetResult($result);

            return $result;
        }

        $lastRunDatetime = $this->marker->getLastExportMarkByTypeAndLocale($type, $locale);
        $startTime = new DateTime();

        $baseQuery = $this->queryContainer->createBasicExportableQuery($type, $locale, $lastRunDatetime);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ITEM_ID, CollectorConfig::COLLECTOR_RESOURCE_ID);
        $baseQuery->setFormatter($this->getFormatter());

        $collectorPlugin = $this->collectorPlugins[$type];
        $collectorPlugin->run(
            $baseQuery,
            $locale,
            $result,
            $this->reader,
            $this->writer,
            $this->touchUpdater,
            $output
        );

        $this->finishExport($result, $type, $startTime);

        return $result;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param string $type
     * @param \DateTime $startTime
     *
     * @return void
     */
    protected function finishExport(BatchResultInterface $batchResult, $type, DateTime $startTime)
    {
        if (!$batchResult->isFailed()) {
            $this->marker->setLastExportMarkByTypeAndLocale(
                $type,
                $batchResult->getProcessedLocale(),
                $startTime
            );
        }
    }

    /**
     * @return \Propel\Runtime\Formatter\AbstractFormatter
     */
    protected function getFormatter()
    {
        return new PropelArraySetFormatter();
    }

    /**
     * @param string $type
     *
     * @return bool
     */
    protected function isCollectorRegistered($type)
    {
        return array_key_exists($type, $this->collectorPlugins);
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     *
     * @return void
     */
    protected function resetResult(BatchResultInterface $result)
    {
        $result->setProcessedCount(0);
        $result->setIsFailed(false);
        $result->setTotalCount(0);
        $result->setDeletedCount(0);
    }
}
