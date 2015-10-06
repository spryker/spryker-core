<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\ZedRequest\Client\Fixture;

use SprykerEngine\Shared\Transfer\AbstractTransfer;

class Transfer extends AbstractTransfer
{

    /**
     * @var string
     */
    protected $key;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     *
     * @return Transfer
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

}
