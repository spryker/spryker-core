<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Collector\Search;

use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\Search\ConfigurableSearchReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\Search\ConfigurableSearchWriterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\Business\Model\BatchResultInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractConfigurableSearchPdoCollector extends AbstractSearchPdoCollector
{
    /**
     * @return \Generated\Shared\Transfer\SearchCollectorConfigurationTransfer
     */
    abstract protected function getCollectorConfiguration();

    /**
     * @param \Spryker\Service\UtilDataReader\Model\BatchIterator\CountableIteratorInterface $batchCollection
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Model\BatchResultInterface $batchResult
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $storeReader
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    public function exportDataToStore(
        CountableIteratorInterface $batchCollection,
        TouchUpdaterInterface $touchUpdater,
        BatchResultInterface $batchResult,
        ReaderInterface $storeReader,
        WriterInterface $storeWriter,
        LocaleTransfer $locale,
        OutputInterface $output
    ) {
        $storeReader = $this->configureReader($storeReader);
        $storeWriter = $this->configureWriter($storeWriter);

        parent::exportDataToStore($batchCollection, $touchUpdater, $batchResult, $storeReader, $storeWriter, $locale, $output);
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\TouchUpdaterInterface $touchUpdater
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     * @param string $itemType
     *
     * @return int
     */
    public function deleteDataFromStore(
        TouchUpdaterInterface $touchUpdater,
        WriterInterface $storeWriter,
        $itemType
    ) {
        $storeWriter = $this->configureWriter($storeWriter);

        return parent::deleteDataFromStore($touchUpdater, $storeWriter, $itemType);
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $storeReader
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface
     */
    protected function configureReader(ReaderInterface $storeReader)
    {
        if (!$storeReader instanceof ConfigurableSearchReaderInterface) {
            return $storeReader;
        }

        /** @var \Spryker\Zed\Collector\Business\Exporter\Reader\Search\ConfigurableSearchReaderInterface $configurableSearchReader */
        $configurableSearchReader = $this->cloneReader($storeReader);
        $configurableSearchReader->setSearchCollectorConfiguration($this->getCollectorConfiguration());

        return $configurableSearchReader;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    protected function configureWriter(WriterInterface $storeWriter)
    {
        if (!$storeWriter instanceof ConfigurableSearchWriterInterface) {
            return $storeWriter;
        }

        /** @var \Spryker\Zed\Collector\Business\Exporter\Writer\Search\ConfigurableSearchWriterInterface $configurableSearchWriter */
        $configurableSearchWriter = $this->cloneWriter($storeWriter);
        $configurableSearchWriter->setSearchCollectorConfiguration($this->getCollectorConfiguration());

        return $configurableSearchWriter;
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $storeReader
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface|\Spryker\Zed\Collector\Business\Exporter\Reader\Search\ConfigurableSearchReaderInterface
     */
    protected function cloneReader(ReaderInterface $storeReader)
    {
        return unserialize(serialize($storeReader));
    }

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $storeWriter
     *
     * @return \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface|\Spryker\Zed\Collector\Business\Exporter\Writer\Search\ConfigurableSearchWriterInterface
     */
    protected function cloneWriter(WriterInterface $storeWriter)
    {
        return unserialize(serialize($storeWriter));
    }
}
