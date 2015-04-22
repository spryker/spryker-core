<?php

namespace SprykerFeature\Zed\Product\Business\Importer\Reader\File;

/**
 * Interface IteratorReaderInterface
 */
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