<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductSearch\Business\Collector;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToCollectorInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductSearchCollectorRunner implements ProductSearchCollectorRunnerInterface
{
    /**
     * @var \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface
     */
    protected $collector;

    /**
     * @var \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToCollectorInterface
     */
    protected $collectorFacade;

    /**
     * @param \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface $collector
     * @param \Spryker\Zed\ProductSearch\Dependency\Facade\ProductSearchToCollectorInterface $collectorFacade
     */
    public function __construct(
        DatabaseCollectorInterface $collector,
        ProductSearchToCollectorInterface $collectorFacade
    ) {
        $this->collector = $collector;
        $this->collectorFacade = $collectorFacade;
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $dataReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function run(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $localeTransfer,
        BatchResultInterface $result,
        ReaderInterface $dataReader,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ): void {
        $this->collectorFacade->runCollector(
            $this->collector,
            $baseQuery,
            $localeTransfer,
            $result,
            $dataReader,
            $dataWriter,
            $touchUpdater,
            $output,
        );
    }
}
