<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Reader\File;

interface IteratorReaderInterface
{
    /**
     * @param \SplFileInfo $file
     * @param bool $hasHeadingRow
     *
     * @return \Iterator
     */
    public function getIteratorFromFile(\SplFileInfo $file, $hasHeadingRow = true);
}