<?php
namespace SprykerFeature\Zed\Library\Import;

interface ReaderInterface
{
    /**
     * @param mixed $inputData
     * @return Input
     * @throws Exception\SourceNotReadableException
     */
    public function read($inputData);
}
