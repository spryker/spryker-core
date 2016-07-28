<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\FactFinder\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\FactFinder\Business\FactFinderBusinessFactory getFactory()
 */
class FactFinderFacade extends AbstractFacade implements FactFinderFacadeInterface
{

    /**
     * Specification:
     * - search request
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\FactFinderSearchResponseTransfer
     */
    public function search(QuoteTransfer $quoteTransfer)
    {
        $this->getFactory()
            ->createSearchRequest()
            ->request($quoteTransfer);
    }

    /**
     * @param string $locale
     * @param string $type
     * @param string $number
     *
     * @return mixed
     */
    public function getFactFinderCsv($locale, $type, $number = '')
    {
        $localeTransfer = new LocaleTransfer();
        $localeTransfer->setLocaleName($locale);

        $fileName = $this->getFactory()
            ->getCollectorFacade()
            ->getCsvFileName($type, $localeTransfer, $number);

        $directory = $this->getFactory()
            ->getFactFinderConfig()
            ->getCsvDirectory();
        
        return file_get_contents(
            $directory . '/' . $fileName
        );
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
    public function runFactFinderCategoryCollector(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $localeTransfer,
        BatchResultInterface $result,
        ReaderInterface $dataReader,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {

        $collectorClass = $this->getFactory()
            ->createFactFinderCategoryCollectorClassName();

        $this->getFactory()->getCollectorFacade()
            ->runFileCategoryCollector(
                $baseQuery,
                $localeTransfer,
                $result,
                $dataReader,
                $dataWriter,
                $touchUpdater,
                $output,
                $collectorClass
            );
    }

    /**
     * @param \Orm\Zed\Touch\Persistence\SpyTouchQuery $baseQuery
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $result
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $dataReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $dataWriter
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     *
     * @return void
     */
    public function runFactFinderProductCollector(
        SpyTouchQuery $baseQuery,
        LocaleTransfer $localeTransfer,
        BatchResultInterface $result,
        ReaderInterface $dataReader,
        WriterInterface $dataWriter,
        TouchUpdaterInterface $touchUpdater,
        OutputInterface $output
    ) {

        $collectorClass = $this->getFactory()
            ->createFactFinderProductCollectorClassName();

        $this->getFactory()->getCollectorFacade()
            ->runFileProductCollector(
                $baseQuery,
                $localeTransfer,
                $result,
                $dataReader,
                $dataWriter,
                $touchUpdater,
                $output,
                $collectorClass
            );
    }



}
