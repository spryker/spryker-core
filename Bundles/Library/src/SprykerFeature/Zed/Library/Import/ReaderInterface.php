<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Library\Import;

interface ReaderInterface
{

    /**
     * @param mixed $inputData
     *
     * @throws Exception\SourceNotReadableException
     *
     * @return Input
     */
    public function read($inputData);

}
