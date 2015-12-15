<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\SequenceNumber\Business\Generator;

interface RandomNumberGeneratorInterface
{

    /**
     * @return int
     */
    public function generate();

}
