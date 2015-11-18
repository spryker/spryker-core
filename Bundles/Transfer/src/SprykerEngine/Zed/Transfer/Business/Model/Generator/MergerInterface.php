<?php

/**
 * (c) Spryker Systems GmbH copyright protected.
 */

namespace SprykerEngine\Zed\Transfer\Business\Model\Generator;

interface MergerInterface
{

    /**
     * @param array $transferDefinitions
     *
     * @return array
     */
    public function merge(array $transferDefinitions);

}
