<?php
namespace SprykerFeature\Zed\Library\Import;

interface WriterInterface
{
    /**
     * @param array $data Array of Rows
     */
    public function write(array $data);
}
