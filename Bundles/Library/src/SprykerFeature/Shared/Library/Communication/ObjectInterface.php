<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Shared\Library\Communication;

interface ObjectInterface
{

    /**
     * @param array $values
     */
    public function fromArray(array $values);

    /**
     * @return array
     */
    public function toArray();

}
