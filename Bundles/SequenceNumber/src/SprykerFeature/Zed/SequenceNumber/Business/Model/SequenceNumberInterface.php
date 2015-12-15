<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SequenceNumber\Business\Model;

interface SequenceNumberInterface
{

    /**
     * @return int
     */
    public function generate();

}
