<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import\Reader;

use SprykerFeature\Zed\Library\Import\Input;
use SprykerFeature\Zed\Library\Import\ReaderInterface;
use SprykerFeature\Zed\Library\Import\Exception;
use Psr\Log\LoggerInterface;

class ChainReader implements ReaderInterface
{

    /**
     * @var array
     */
    private $readers = [];

    /**
     * @var ReaderInterface[]
     */
    private $sortedReaders = [];

    /**
     * @var LoggerInterface
     */
    private $logger = null;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @param ReaderInterface $reader
     * @param int $priority
     */
    public function add(ReaderInterface $reader, $priority = 0)
    {
        if (empty($this->readers[$priority])) {
            $this->readers[$priority] = [];
        }

        $this->readers[$priority][] = $reader;
        $this->sortedReaders = [];
    }

    /**
     * @return ReaderInterface[]
     */
    public function all()
    {
        if (empty($this->sortedReaders)) {
            $this->sortedReaders = $this->sortReaders();
        }

        return $this->sortedReaders;
    }

    /**
     * Sort readers by priority.
     * The highest priority number is the highest priority (reverse sorting)
     *
     * @return ReaderInterface[]
     */
    protected function sortReaders()
    {
        $sortedReaders = [];
        krsort($this->readers);

        foreach ($this->readers as $reader) {
            $sortedReaders = array_merge($sortedReaders, $reader);
        }

        return $sortedReaders;
    }

    /**
     * @param mixed $inputData
     *
     * @throws Exception\SourceNotReadableException
     *
     * @return Input
     */
    public function read($inputData)
    {
        foreach ($this->all() as $reader) {
            try {
                return $reader->read($inputData);
            } catch (Exception\SourceNotReadableException $exception) {
                if ($this->logger) {
                    $this->logger->info('Reader ' . get_class($reader) . ' was not able to read, message "' . $exception->getMessage() . '"');
                }
            }
        }

        throw new Exception\SourceNotReadableException('None of the readers in the chain matched');
    }

}
