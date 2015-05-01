<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Reader\File;

use League\Csv\Reader;

/**
 * Class CsvReader
 */
class CsvReader implements IteratorReaderInterface
{
    /**
     * @var string
     */
    protected $delimiter = ',';

    /**
     * @var string
     */
    protected $enclosure = '"';

    /**
     * @var string
     */
    protected $escape = '\\';

    /**
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     */
    public function __construct($delimiter = ',', $enclosure = '"', $escape = '\\')
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    /**
     * @param \SplFileInfo $file
     * @param bool $hasHeadingRow
     *
     * @return \Iterator
     */
    public function getIteratorFromFile(\SplFileInfo $file, $hasHeadingRow = true)
    {
        $reader = Reader::createFromPath($file->getRealPath());

        $reader->setDelimiter($this->delimiter);
        $reader->setEnclosure($this->enclosure);
        $reader->setEscape($this->escape);

        $iterator = $hasHeadingRow ? $reader->fetchAssoc() : $reader->getIterator();

        return $iterator;
    }
}
 