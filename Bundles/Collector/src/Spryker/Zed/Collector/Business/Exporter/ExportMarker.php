<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use RuntimeException;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;
use Spryker\Zed\Collector\CollectorConfig;
use Spryker\Zed\Collector\Dependency\Facade\CollectorToStoreFacadeInterface;

class ExportMarker implements MarkerInterface
{
    /**
     * @var string
     */
    protected const FORMAT_DATETIME = 'Y-m-d H:i:s';

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface
     */
    protected $writer;

    /**
     * @var \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface
     */
    protected $reader;

    /**
     * @var \Spryker\Shared\KeyBuilder\KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @var \Spryker\Zed\Collector\CollectorConfig
     */
    protected $collectorConfig;

    /**
     * @var \Spryker\Zed\Collector\Dependency\Facade\CollectorToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $writer
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $reader
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     * @param \Spryker\Zed\Collector\CollectorConfig $collectorConfig
     * @param \Spryker\Zed\Collector\Dependency\Facade\CollectorToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        WriterInterface $writer,
        ReaderInterface $reader,
        KeyBuilderInterface $keyBuilder,
        CollectorConfig $collectorConfig,
        CollectorToStoreFacadeInterface $storeFacade
    ) {
        $this->writer = $writer;
        $this->reader = $reader;
        $this->keyBuilder = $keyBuilder;
        $this->collectorConfig = $collectorConfig;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     *
     * @throws \RuntimeException
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $localeTransfer)
    {
        /** @var string|null $lastTimeStamp */
        $lastTimeStamp = $this->reader
            ->read($this->keyBuilder->generateKey($exportType, $localeTransfer->getLocaleName()), $exportType);

        if (!$lastTimeStamp) {
            $lastTimeStamp = '2000-01-01 00:00:00';
        }

        $dateTime = DateTime::createFromFormat(static::FORMAT_DATETIME, $lastTimeStamp);
        if ($dateTime === false) {
            throw new RuntimeException(sprintf('Could not create date from timestamp `%s`', $lastTimeStamp));
        }

        return $dateTime;
    }

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $localeTransfer
     * @param \DateTime $timestamp
     *
     * @return void
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $localeTransfer, DateTime $timestamp)
    {
        $timestampKey = $this->keyBuilder->generateKey(
            $exportType,
            $localeTransfer->getLocaleName(),
            $this->storeFacade->getCurrentStore()->getNameOrFail(),
        );
        $this->writer->write([$timestampKey => $timestamp->format(static::FORMAT_DATETIME)]);
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTimestamps(array $keys)
    {
        if (!$this->collectorConfig->isCollectorEnabled()) {
            return true;
        }

        return $this->writer->delete($keys);
    }
}
