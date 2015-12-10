<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Propel\Runtime\Formatter\AbstractFormatter;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Business\Model\FailedResultInterface;
use SprykerFeature\Zed\Collector\CollectorConfig;
use SprykerFeature\Zed\Collector\Dependency\Plugin\CollectorPluginInterface;
use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractExporter implements ExporterInterface
{

    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    /**
     * @var CollectorPluginInterface[]
     */
    protected $collectorPlugins = [];

    /**
     * @var FailedResultInterface
     */
    protected $failedResultPrototype;

    /**
     * @var BatchResultInterface
     */
    protected $batchResultPrototype;

    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @var TouchUpdaterInterface
     */
    protected $touchUpdater;

    /**
     * @var MarkerInterface
     */
    protected $marker;

    /**
     * @var TouchQueryContainer
     */
    protected $queryContainer;

    /**
     * @param TouchQueryContainer $queryContainer
     * @param WriterInterface $writer
     * @param MarkerInterface $marker
     * @param FailedResultInterface $failedResultPrototype
     * @param BatchResultInterface $batchResultPrototype
     * @param TouchUpdaterInterface $touchUpdater
     */
    public function __construct(
        TouchQueryContainer $queryContainer,
        WriterInterface $writer,
        MarkerInterface $marker,
        FailedResultInterface $failedResultPrototype,
        BatchResultInterface $batchResultPrototype,
        TouchUpdaterInterface $touchUpdater
    ) {
        $this->queryContainer = $queryContainer;
        $this->writer = $writer;
        $this->marker = $marker;
        $this->failedResultPrototype = $failedResultPrototype;
        $this->batchResultPrototype = $batchResultPrototype;
        $this->touchUpdater = $touchUpdater;
    }

    /**
     * @param string $touchItemType
     * @param CollectorPluginInterface $plugin
     *
     * @return void
     */
    public function addCollectorPlugin($touchItemType, CollectorPluginInterface $plugin)
    {
        $this->collectorPlugins[$touchItemType] = $plugin;
    }

    /**
     * @return CollectorPluginInterface[]
     */
    public function getCollectorPlugins()
    {
        return $this->collectorPlugins;
    }

    /**
     * @param string $type
     * @param LocaleTransfer $locale
     *
     * @return BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $locale, OutputInterface $output = null)
    {
        $timestamp = $this->createNewTimestamp();

        $result = clone $this->batchResultPrototype;
        $result->setProcessedLocale($locale);

        if (!$this->isCollectorRegistered($type)) {
            $this->resetResult($result);

            return $result;
        }

        $lastRunDatetime = $this->marker->getLastExportMarkByTypeAndLocale($type, $locale);

        $baseQuery = $this->queryContainer->createBasicExportableQuery($type, $locale, $lastRunDatetime);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ITEM_ID, CollectorConfig::COLLECTOR_RESOURCE_ID);
        $baseQuery->setFormatter($this->getFormatter());

        $collectorPlugin = $this->collectorPlugins[$type];
        $collectorPlugin->setOutput($output);
        $collectorPlugin->setDataWriter($this->writer);
        $collectorPlugin->setTouchUpdater($this->touchUpdater);
        $collectorPlugin->run($baseQuery, $locale, $result);

        $this->finishExport($result, $type, $timestamp);

        return $result;
    }

    /**
     * @param BatchResultInterface $batchResult
     * @param $type
     * @param string $timestamp
     *
     * @return void
     */
    protected function finishExport(BatchResultInterface $batchResult, $type, $timestamp)
    {
        if (!$batchResult->isFailed()) {
            $this->marker->setLastExportMarkByTypeAndLocale($type, $batchResult->getProcessedLocale(), $timestamp);
        }
    }

    /**
     * @return AbstractFormatter
     */
    protected function getFormatter()
    {
        return new PropelArraySetFormatter();
    }

    /**
     * @return string
     */
    protected function createNewTimestamp()
    {
        $timestamp = (new \DateTime())->format(self::DATE_TIME_FORMAT);

        return $timestamp;
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
     * @param BatchResultInterface $result
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
