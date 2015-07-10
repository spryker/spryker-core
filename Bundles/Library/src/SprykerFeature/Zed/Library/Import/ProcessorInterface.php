<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

interface ProcessorInterface
{

    /**
     * @param ReaderInterface $reader
     */
    public function setReader(ReaderInterface $reader);

    /**
     * @param TypeDetectorInterface $typeDetector
     */
    public function setTypeDetector(TypeDetectorInterface $typeDetector);

    /**
     * @param ProcessInterface[] $processList
     */
    public function setProcesses($processList);

    /**
     * @param ProcessInterface $process
     */
    public function addProcess(ProcessInterface $process);

    /**
     * @param mixed $data
     *
     * @throws Exception\ProcessorFailedException
     * @throws Exception\ImportTypeNotDetectedException
     * @throws Exception\SourceNotReadableException
     * @throws Exception\SourceNotValidException
     */
    public function process($data);

}
