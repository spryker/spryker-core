<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Client\ZedRequest\Client\Fixture;

use Spryker\Shared\Transfer\AbstractTransfer;
use Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException;

class TestTransfer extends AbstractTransfer
{

    const FOO = 'foo';

    /**
     * @var string
     */
    protected $foo;

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::FOO => [
            'type' => 'string',
            'name_underscore' => 'foo',
            'is_collection' => false,
            'is_transfer' => false,
        ],
    ];

    /**
     * @param string $foo
     *
     * @return self
     */
    public function setFoo($foo)
    {
        $this->foo = $foo;
        $this->addModifiedProperty(self::FOO);

        return $this;
    }

    /**
     * @return string
     */
    public function getFoo()
    {
        return $this->foo;
    }

    /**
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return self
     */
    public function requireFoo()
    {
        $this->assertPropertyIsSet(self::FOO);

        return $this;
    }

}
