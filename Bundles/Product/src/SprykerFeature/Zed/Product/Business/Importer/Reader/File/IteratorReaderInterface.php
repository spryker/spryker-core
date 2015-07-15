<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Product\Business\Importer\Reader\File;

interface IteratorReaderInterface
{

    /**
     * @param \SplFileInfo $file
     *
     * @return \Iterator
     */
    public function getIteratorFromFile(\SplFileInfo $file);

    /**
     * @param \SplFileInfo $file
     *
     * @return array
     */
    public function getArrayFromFile(\SplFileInfo $file);

}
