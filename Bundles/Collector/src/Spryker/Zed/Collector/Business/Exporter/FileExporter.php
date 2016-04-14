<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Spryker\Shared\Library\Writer\Csv\CsvFormatterInterface;
use Spryker\Shared\Library\Writer\Csv\CsvWriter;
use Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterPathConstructor;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\Business\Model\FailedResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
     * @param \Spryker\Zed\Touch\Persistence\TouchQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $writer
     * @param \Spryker\Zed\Collector\Business\Exporter\MarkerInterface $marker
     * @param \Spryker\Zed\Collector\Business\Model\FailedResultInterface $failedResultPrototype
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResultPrototype
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\File\FileWriterPathConstructor $pathConstructor
     * @param \Spryker\Shared\Library\Writer\Csv\CsvFormatterInterface $fileFormatter
     */
    public function __construct(
        TouchQueryContainerInterface $queryContainer,
        WriterInterface $writer,
        MarkerInterface $marker,
        FailedResultInterface $failedResultPrototype,
        BatchResultInterface $batchResultPrototype,
        TouchUpdaterInterface $touchUpdater,
        FileWriterPathConstructor $pathConstructor,
        CsvFormatterInterface $fileFormatter
    ) {
        parent::__construct($queryContainer, $writer, $marker, $failedResultPrototype, $batchResultPrototype, $touchUpdater);
        $this->pathConstructor = $pathConstructor;
        $this->fileFormatter = $fileFormatter;
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

        $lastTimeStamp = '2000-01-01 00:00:00';
        $lastRunDatetime = \DateTime::createFromFormat('Y-m-d H:i:s', $lastTimeStamp);

        $baseQuery = $this->queryContainer->createBasicExportableQuery($type, $locale, $lastRunDatetime);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ID_TOUCH, CollectorConfig::COLLECTOR_TOUCH_ID);
        $baseQuery->withColumn(SpyTouchTableMap::COL_ITEM_ID, CollectorConfig::COLLECTOR_RESOURCE_ID);
        $baseQuery->setFormatter($this->getFormatter());

        $collectorPlugin = $this->collectorPlugins[$type];

        $csvWriter = new CsvWriter($this->pathConstructor->getExportPath($type, $locale));
        $csvWriter->setCsvFormat($this->fileFormatter);
        $this->writer->setWriterAdapter($csvWriter);

        $collectorPlugin->run($baseQuery, $locale, $result, $this->writer, $this->touchUpdater, $output);

        $this->finishExport(
            $result,
            $type,
            $lastRunDatetime
        );

        return $result;
    }

}
