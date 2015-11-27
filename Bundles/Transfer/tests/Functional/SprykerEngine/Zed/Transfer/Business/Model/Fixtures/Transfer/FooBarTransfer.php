<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use SprykerEngine\Shared\Transfer\AbstractTransfer;
use SprykerEngine\Shared\Transfer\Exception\RequiredTransferPropertyException;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class FooBarTransfer extends AbstractTransfer
{

    const NAME = 'name';

    const BLA = 'bla';

    const SELF_REFERENCE = 'selfReference';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $bla;

    /**
     * @var \ArrayObject|FooBarTransfer[]
     */
    protected $selfReference;

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::NAME => [
          'type' => 'string',
          'name_underscore' => 'name',
          'is_collection' => false,
          'is_transfer' => false,
        ],
        self::BLA => [
          'type' => 'int',
          'name_underscore' => 'bla',
          'is_collection' => false,
          'is_transfer' => false,
        ],
        self::SELF_REFERENCE => [
          'type' => 'Generated\Shared\Transfer\FooBarTransfer',
          'name_underscore' => 'self_reference',
          'is_collection' => true,
          'is_transfer' => true,
        ],
    ];

    public function __construct()
    {
        $this->selfReference = new \ArrayObject();
    }

    /**
     * @param string $name
     *
     * @bundle Test
     *
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->addModifiedProperty(self::NAME);

        return $this;
    }

    /**
     * @bundle Test
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @bundle Test
     *
     * @return self
     */
    public function requireName()
    {
        $this->assertPropertyIsSet(self::NAME);

        return $this;
    }

    /**
     * @param int $bla
     *
     * @bundle Test
     *
     * @return self
     */
    public function setBla($bla)
    {
        $this->bla = $bla;
        $this->addModifiedProperty(self::BLA);

        return $this;
    }

    /**
     * @bundle Test
     *
     * @return int
     */
    public function getBla()
    {
        return $this->bla;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @bundle Test
     *
     * @return self
     */
    public function requireBla()
    {
        $this->assertPropertyIsSet(self::BLA);

        return $this;
    }

    /**
     * @param \ArrayObject|FooBarTransfer[] $selfReference
     *
     * @bundle Test
     *
     * @return self
     */
    public function setSelfReference(\ArrayObject $selfReference)
    {
        $this->selfReference = $selfReference;
        $this->addModifiedProperty(self::SELF_REFERENCE);

        return $this;
    }

    /**
     * @bundle Test
     *
     * @return FooBarTransfer[]
     */
    public function getSelfReference()
    {
        return $this->selfReference;
    }

    /**
     * @param FooBarTransfer $selfReference
     *
     * @bundle Test
     *
     * @return self
     */
    public function addSelfReference(FooBarTransfer $selfReference)
    {
        $this->selfReference[] = $selfReference;
        $this->addModifiedProperty(self::SELF_REFERENCE);

        return $this;
    }

    /**
     * @throws RequiredTransferPropertyException
     *
     * @bundle Test
     *
     * @return self
     */
    public function requireSelfReference()
    {
        $this->assertCollectionPropertyIsSet(self::SELF_REFERENCE);

        return $this;
    }

}
