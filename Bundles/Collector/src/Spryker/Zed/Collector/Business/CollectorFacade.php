<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business;

use Generated\Shared\Transfer\LocaleTransfer;
use Orm\Zed\Touch\Persistence\SpyTouchQuery;
use Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Spryker\Zed\Kernel\Business\AbstractFacade;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method \Spryker\Zed\Collector\Business\CollectorBusinessFactory getFactory()
 */
class CollectorFacade extends AbstractFacade implements CollectorFacadeInterface
{
    /**
     * Specification:
     * - Runs storage exporter collectors for all available stores, locales and collector types.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportStorage(OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->exportStorage($output);
    }

    /**
     * Specification:
     * - Runs storage exporter collectors for the given locale and all available collector types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportStorageByLocale(LocaleTransfer $locale, OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->exportStorageByLocale($locale, $output);
    }

    /**
     * Specification:
     * - Runs search exporter collectors for all available stores, locales and collector types.
     *
     * @api
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportSearch(OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesSearchExporter();

        return $exporter->exportStorage($output);
    }

    /**
     * Specification:
     * - Runs search exporter collectors for the given locale and all available collector types.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return \Spryker\Zed\Collector\Business\Model\BatchResultInterface[]
     */
    public function exportSearchByLocale(LocaleTransfer $locale, OutputInterface $output)
    {
        $exporter = $this->getFactory()->createYvesSearchExporter();

        return $exporter->exportStorageByLocale($locale, $output);
    }

    /**
     * Specification:
     * - Deletes all metadata information from the current search index mapping.
     *
     * @api
     *
     * @param array $keys
     *
     * @return bool
     */
    public function deleteSearchTimestamps(array $keys = [])
    {
        return $this->getFactory()->createSearchMarker()->deleteTimestamps($keys);
    }

    /**
     * Specification:
     * - Deletes all the provided keys from storage.
     *
     * @api
     *
     * @param array $keys
     *
     * @return bool
     */
    public function deleteStorageTimestamps(array $keys = [])
    {
        return $this->getFactory()->createStorageMarker()->deleteTimestamps($keys);
    }

    /**
     * Specification:
     * - Returns all persisted collector types from database.
     *
     * @api
     *
     * @return array
     */
    public function getAllCollectorTypes()
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->getAllCollectorTypes();
    }

    /**
     * Specification:
     * - Returns the types of all collector plugins that has been registered in the StorageExporter.
     *
     * @api
     *
     * @return array
     */
    public function getEnabledCollectorTypes()
    {
        $exporter = $this->getFactory()->createYvesStorageExporter();

        return $exporter->getEnabledCollectorTypes();
    }

    /**
     * Specification:
     * - Runs collectors defined in the project and synchronizes data with stores (add, update, delete)
     *
     * @api
     *
     * @param \Spryker\Zed\Collector\Business\Collector\DatabaseCollectorInterface $collector *
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

        $this->getFactory()
            ->createCollectorManager()
            ->runCollector(
                $collector,
                $baseQuery,
                $locale,
                $result,
                $dataReader,
                $dataWriter,
                $touchUpdater,
                $output
            );
    }
}
