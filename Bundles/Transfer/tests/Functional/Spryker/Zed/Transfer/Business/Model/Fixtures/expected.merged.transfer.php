<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use Spryker\Shared\Transfer\AbstractTransfer;

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
     * @var \ArrayObject|\Generated\Shared\Transfer\FooBarTransfer[]
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

    /**
     * @bundle Test
     *
     * @param string $name
     *
     * @return $this
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
     * @bundle Test
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireName()
    {
        $this->assertPropertyIsSet(self::NAME);

        return $this;
    }

    /**
     * @bundle Test|Test2
     *
     * @param int $bla
     *
     * @return $this
     */
    public function setBla($bla)
    {
        $this->bla = $bla;
        $this->addModifiedProperty(self::BLA);

        return $this;
    }

    /**
     * @bundle Test|Test2
     *
     * @return int
     */
    public function getBla()
    {
        return $this->bla;
    }

    /**
     * @bundle Test|Test2
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireBla()
    {
        $this->assertPropertyIsSet(self::BLA);

        return $this;
    }

    /**
     * @bundle Test2
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\FooBarTransfer[] $selfReference
     *
     * @return $this
     */
    public function setSelfReference(\ArrayObject $selfReference)
    {
        $this->selfReference = $selfReference;
        $this->addModifiedProperty(self::SELF_REFERENCE);

        return $this;
    }

    /**
     * @bundle Test2
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FooBarTransfer[]
     */
    public function getSelfReference()
    {
        return $this->selfReference;
    }

    /**
     * @bundle Test2
     *
     * @param \Generated\Shared\Transfer\FooBarTransfer $selfReference
     *
     * @return $this
     */
    public function addSelfReference(FooBarTransfer $selfReference)
    {
        $this->selfReference[] = $selfReference;
        $this->addModifiedProperty(self::SELF_REFERENCE);

        return $this;
    }

    /**
     * @bundle Test2
     *
     * @throws \Spryker\Shared\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireSelfReference()
    {
        $this->assertCollectionPropertyIsSet(self::SELF_REFERENCE);

        return $this;
    }

}
