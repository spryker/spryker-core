<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterBuilder;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Business\Model\FailedResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use \DateTime;

class FileExporter extends AbstractExporter
{

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterPathConstructor
     */
    protected $pathConstructor;

    /**
     * @var \Spryker\Shared\Library\Writer\Csv\CsvFormatterInterface
     */
    protected $fileFormatter;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterBuilder
     */
    protected $writerBuilder;

    /**
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterBuilder $fileWriterBuilder
     * @param \Spryker\Zed\Collector\Business\Exporter\MarkerInterface $marker
     * @param \Spryker\Zed\Collector\Business\Model\FailedResultInterface $failedResultPrototype
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResultPrototype
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     */
    public function __construct(
        TouchQueryContainerInterface $queryContainer,
        FileWriterBuilder $fileWriterBuilder,
        MarkerInterface $marker,
        FailedResultInterface $failedResultPrototype,
        BatchResultInterface $batchResultPrototype,
        TouchUpdaterInterface $touchUpdater
    ) {
        $this->queryContainer = $queryContainer;
        $this->marker = $marker;
        $this->failedResultPrototype = $failedResultPrototype;
        $this->batchResultPrototype = $batchResultPrototype;
        $this->touchUpdater = $touchUpdater;
        $this->writerBuilder = $fileWriterBuilder;
    }

    /**
     * @param string $type
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface
     */
    public function exportByType($type, LocaleTransfer $localeTransfer, OutputInterface $output)
    {
        $result = clone $this->batchResultPrototype;
        $result->setProcessedLocale($localeTransfer);

        if (!$this->isCollectorRegistered($type)) {
            $this->resetResult($result);

            return $result;
        }

        $lastTimeStamp = '2000-01-01 00:00:00';
        $lastRunDatetime = new DateTime($lastTimeStamp);

        $baseQuery = $this->queryContainer->createBasicExportableQuery($type, $localeTransfer, $lastRunDatetime);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ITEM_ID, CollectorConfig::COLLECTOR_RESOURCE_ID);
        $baseQuery->setFormatter($this->getFormatter());

        $collectorPlugin = $this->collectorPlugins[$type];

        $this->writer = $this->writerBuilder->build($type, $localeTransfer);

        $collectorPlugin->run($baseQuery, $localeTransfer, $result, $this->writer, $this->touchUpdater, $output);

        $this->finishExport(
            $result,
            $type,
            $lastRunDatetime
        );

        return $result;
    }

}
