<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Collector\Business\Exporter;

use DateTime;
use Generated\Shared\Transfer\LocaleTransfer;
use Spryker\Shared\KeyBuilder\KeyBuilderInterface;
use Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class ExportMarker implements MarkerInterface
{
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
     * @param \Spryker\Zed\Collector\Business\Exporter\Writer\WriterInterface $writer
     * @param \Spryker\Zed\Collector\Business\Exporter\Reader\ReaderInterface $reader
     * @param \Spryker\Shared\KeyBuilder\KeyBuilderInterface $keyBuilder
     */
    public function __construct(WriterInterface $writer, ReaderInterface $reader, KeyBuilderInterface $keyBuilder)
    {
        $this->writer = $writer;
        $this->reader = $reader;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $locale)
    {
        /** @var string|null $lastTimeStamp */
        $lastTimeStamp = $this->reader
            ->read($this->keyBuilder->generateKey($exportType, $locale->getLocaleName()), $exportType);

        if (!$lastTimeStamp) {
            $lastTimeStamp = '2000-01-01 00:00:00';
        }

        return DateTime::createFromFormat('Y-m-d H:i:s', $lastTimeStamp);
    }

    /**
     * @param string $exportType
     * @param \Generated\Shared\Transfer\LocaleTransfer $locale
     * @param \DateTime $timestamp
     *
     * @return void
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleTransfer $locale, DateTime $timestamp)
    {
        $timestampKey = $this->keyBuilder->generateKey($exportType, $locale->getLocaleName());
        $this->writer->write([$timestampKey => $timestamp->format('Y-m-d H:i:s')]);
    }

    /**
     * @param array $keys
     *
     * @return bool
     */
    public function deleteTimestamps(array $keys)
    {
        return $this->writer->delete($keys);
    }
}
