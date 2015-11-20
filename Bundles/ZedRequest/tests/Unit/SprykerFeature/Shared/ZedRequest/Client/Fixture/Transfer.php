<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Shared\ZedRequest\Client\Fixture;

use SprykerEngine\Shared\Transfer\AbstractTransfer;
use SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException;

class Transfer extends AbstractTransfer
{

    const KEY = 'key';

    /**
     * @var string
     */
    protected $key;

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::KEY => [
            'type' => 'string',
            'name_underscore' => 'key',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @param string $key
     *
     * @return self
     */
    public function setKey($key)
    {
        $this->key = $key;
        $this->addModifiedProperty(self::KEY);

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireKey()
    {
        $this->assertPropertyIsSet(self::KEY);

        return $this;
    }

}
