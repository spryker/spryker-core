<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Shared\ZedRequest\Client;

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
