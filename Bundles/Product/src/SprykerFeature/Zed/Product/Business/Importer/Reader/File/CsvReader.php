<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Reader\File;

use League\Csv\Reader;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

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
     *
     * @throws FileNotFoundException
     *
     * @return \Iterator
     */
    public function getIteratorFromFile(\SplFileInfo $file)
    {
        $reader = $this->getFileReader($file);

        return $reader->getIterator();
    }

    /**
     * @param \SplFileInfo $file
     *
     * @return array
     */
    public function getArrayFromFile(\SplFileInfo $file)
    {
        $reader = $this->getFileReader($file);

        return $reader->fetchAssoc();
    }

    /**
     * @param \SplFileInfo $file
     *
     * @throws FileNotFoundException
     *
     * @return Reader
     */
    protected function getFileReader(\SplFileInfo $file)
    {
        $path = $file->getRealPath();
        if (!file_exists($path)) {
            throw new FileNotFoundException('File not found: ' . $path);
        }

        $reader = Reader::createFromPath($path);

        $reader->setDelimiter($this->delimiter);
        $reader->setEnclosure($this->enclosure);
        $reader->setEscape($this->escape);

        return $reader;
    }

}
