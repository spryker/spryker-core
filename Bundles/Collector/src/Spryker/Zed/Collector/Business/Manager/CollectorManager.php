<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Manager;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\Map\SpyTouchTableMap;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Symfony\Component\Console\Output\OutputInterface;

class CollectorManager implements CollectorManagerInterface
{
    /**
     * @var \Spryker\Zed\Collector\CollectorConfig
     */
    protected $collectorConfig;

    /**
     * @param \Spryker\Zed\Collector\CollectorConfig $collectorConfig
     */
    public function __construct(CollectorConfig $collectorConfig)
    {
        $this->collectorConfig = $collectorConfig;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface $collector
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $dataReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function runCollector(
        DatabaseCollectorInterface $collector,
        SpyTouchQuery $baseQuery,
        LocaleTransfer $locale,
        BatchResultInterface $result,
        ReaderInterface $dataReader,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {
        if (!$this->collectorConfig->isCollectorEnabled()) {
            return;
        }

        $itemType = $baseQuery->get(SpyTouchTableMap::COL_ITEM_TYPE);
        $collector->setLocale($locale);
        $collector->deleteDataFromStore($touchUpdater, $dataWriter, $itemType);
        $batchCollection = $collector->collectDataFromDatabase($baseQuery, $locale);
        $collector->exportDataToStore($batchCollection, $touchUpdater, $result, $dataReader, $dataWriter, $locale, $output);
    }
}
