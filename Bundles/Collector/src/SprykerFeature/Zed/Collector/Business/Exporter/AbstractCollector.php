<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Propel\Runtime\Formatter\AbstractFormatter;
use SprykerEngine\Zed\Touch\Persistence\TouchQueryContainer;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use SprykerFeature\Zed\Collector\Business\Model\BatchResultInterface;
use SprykerFeature\Zed\Collector\Business\Model\FailedResultInterface;
use SprykerFeature\Zed\Collector\Dependency\Plugin\CollectorPluginInterface;
use SprykerEngine\Zed\Propel\Business\Formatter\PropelArraySetFormatter;

abstract class AbstractCollector implements ExporterInterface
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
     */
    public function addCollectorPlugin($touchItemType, CollectorPluginInterface $plugin)
    {
        $this->collectorPlugins[$touchItemType] = $plugin;
    }

    /**
     * @param string $type
     * @param LocaleTransfer $locale
     *
     * @return BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $locale)
    {
        $timestamp = $this->createNewTimestamp();

        $result = clone $this->batchResultPrototype;
        $result->setProcessedLocale($locale);

        if (!array_key_exists($type, $this->collectorPlugins)) {
            $result->setProcessedCount(0);
            $result->setIsFailed(false);
            $result->setTotalCount(0);
            $result->setDeletedCount(0);

            return $result;
        }

        $collector = $this->collectorPlugins[$type];

        $lastRunDatetime = $this->marker->getLastExportMarkByTypeAndLocale($type, $locale);

        $baseQuery = $this->queryContainer->createBasicExportableQuery($type, $locale, $lastRunDatetime);
        $baseQuery->setFormatter($this->getFormatter());
        $collector->run($baseQuery, $locale, $result, $this->writer, $this->touchUpdater);

        return $this->finishExport($result, $type, $timestamp);
    }

    /**
     * @param BatchResultInterface $batchResult
     * @param $type
     * @param string $timestamp
     *
     * @return BatchResultInterface
     */
    protected function finishExport(BatchResultInterface $batchResult, $type, $timestamp)
    {
        if (!$batchResult->isFailed()) {
            $this->marker->setLastExportMarkByTypeAndLocale($type, $batchResult->getProcessedLocale(), $timestamp);
        }

        return $batchResult;
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

}
