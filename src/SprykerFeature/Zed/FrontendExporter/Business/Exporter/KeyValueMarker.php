<?php

namespace SprykerFeature\Zed\FrontendExporter\Business\Exporter;

use SprykerEngine\Shared\Dto\LocaleDto;
use SprykerFeature\Shared\FrontendExporter\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Reader\ReaderInterface;
use SprykerFeature\Zed\FrontendExporter\Business\Exporter\Writer\WriterInterface;

class KeyValueMarker implements MarkerInterface
{
    /**
     * @var WriterInterface
     */
    private $writer;
    /**
     * @var ReaderInterface
     */
    private $reader;
    /**
     * @var KeyBuilderInterface
     */
    private $keyBuilder;

    /**
     * @param WriterInterface       $writer
     * @param ReaderInterface       $reader
     * @param KeyBuilderInterface   $keyBuilder
     */
    public function __construct(
        WriterInterface $writer,
        ReaderInterface $reader,
        KeyBuilderInterface $keyBuilder
    ) {
        $this->writer = $writer;
        $this->reader = $reader;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param string $exportType
     * @param LocaleDto $locale
     *
     * @return \DateTime
     */
    public function getLastExportMarkByTypeAndLocale($exportType, LocaleDto $locale)
    {
        $lastTimeStamp = $this->reader->read($this->keyBuilder->generateKey($exportType, $locale));

        if ($lastTimeStamp) {
            $lastDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', $lastTimeStamp);
        } else {
            $lastDateTime = \DateTime::createFromFormat('Y-m-d H:i:s', '2000-01-01 00:00:00');
        }

        return $lastDateTime;
    }

    /**
     * @param string $exportType
     * @param LocaleDto $locale
     */
    public function setLastExportMarkByTypeAndLocale($exportType, LocaleDto $locale)
    {
        $timestampKey = $this->keyBuilder->generateKey($exportType, $locale);
        $this->writer->write([ $timestampKey => (new \DateTime())->format('Y-m-d H:i:s')]);
    }
}
 