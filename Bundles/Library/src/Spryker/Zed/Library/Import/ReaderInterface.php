<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Library\Import;

interface ReaderInterface
{

    /**
     * @param mixed $inputData
     *
     * @return Input
     */
    public function read($inputData);

}
