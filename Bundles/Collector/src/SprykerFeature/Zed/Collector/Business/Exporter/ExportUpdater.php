<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Collector\Business\Exporter;

use SprykerFeature\Shared\Collector\Code\KeyBuilder\KeyBuilderInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Reader\ReaderInterface;
use SprykerFeature\Zed\Collector\Business\Exporter\Writer\WriterInterface;

class ExportUpdater implements UpdaterInterface
{

    /**
     * @var array
     */
    protected $collectedData;

    /**
     * @var WriterInterface
     */
    protected $writer;

    /**
     * @var ReaderInterface
     */
    protected $reader;

    /**
     * @var KeyBuilderInterface
     */
    protected $keyBuilder;

    /**
     * @param WriterInterface $writer
     * @param ReaderInterface $reader
     * @param KeyBuilderInterface $keyBuilder
     */
    public function __construct(WriterInterface $writer, ReaderInterface $reader, KeyBuilderInterface $keyBuilder)
    {
        $this->writer = $writer;
        $this->reader = $reader;
        $this->keyBuilder = $keyBuilder;
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getData($key)
    {
        return $this->reader->read($key, '');
    }

    /**
     * @param $key
     * @param array $dataToAppend
     *
     * @return void
     */
    public function updateData($key, array $dataToAppend)
    {
        $exportedData = $this->getData($key);

        $data = array_merge($exportedData, $dataToAppend);

        $this->writer->write([$key => $data], '');
    }

    /**
     * @param array $data
     *
     * @return void
     */
    public function collect(array $data)
    {
        $this->collectedData = $data;
    }

}
