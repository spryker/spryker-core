<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class FooBarTransfer extends AbstractTransfer
{
    public const NAME = 'name';

    public const BLA = 'bla';

    public const STOCK = 'stock';

    public const SELF_REFERENCE = 'selfReference';

    /**
     * @var string|null
     */
    protected $name;

    /**
     * @var int|null
     */
    protected $bla;

    /**
     * @var \Spryker\DecimalObject\Decimal|null
     */
    protected $stock;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\FooBarTransfer[]
     */
    protected $selfReference;

    /**
     * @var array
     */
    protected $transferPropertyNameMap = [
        'name' => 'name',
        'Name' => 'name',
        'bla' => 'bla',
        'Bla' => 'bla',
        'stock' => 'stock',
        'Stock' => 'stock',
        'self_reference' => 'selfReference',
        'selfReference' => 'selfReference',
        'SelfReference' => 'selfReference',
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::NAME => [
            'type' => 'string',
            'name_underscore' => 'name',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::BLA => [
            'type' => 'int',
            'name_underscore' => 'bla',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::STOCK => [
            'type' => 'Spryker\DecimalObject\Decimal',
            'name_underscore' => 'stock',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => true,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
        self::SELF_REFERENCE => [
            'type' => 'Generated\Shared\Transfer\FooBarTransfer',
            'name_underscore' => 'self_reference',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'is_associative' => false,
            'is_nullable' => false,
        ],
    ];

    /**
     * @module Test
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        $this->modifiedProperties[self::NAME] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireName()
    {
        $this->assertPropertyIsSet(self::NAME);

        return $this;
    }

    /**
     * @module Test|Test2
     *
     * @param int|null $bla
     *
     * @return $this
     */
    public function setBla($bla)
    {
        $this->bla = $bla;
        $this->modifiedProperties[self::BLA] = true;

        return $this;
    }

    /**
     * @module Test|Test2
     *
     * @return int|null
     */
    public function getBla()
    {
        return $this->bla;
    }

    /**
     * @module Test|Test2
     *
     * @return $this
     */
    public function requireBla()
    {
        $this->assertPropertyIsSet(self::BLA);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|int|float|\Spryker\DecimalObject\Decimal|null $stock
     *
     * @return $this
     */
    public function setStock($stock = null)
    {
        if ($stock !== null && !$stock instanceof Decimal) {
            $stock = new Decimal($stock);
        }

        $this->stock = $stock;
        $this->modifiedProperties[self::STOCK] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    public function getStock()
    {
        return $this->stock;
    }

    /**
     * @module Test
     *
     * @return $this
     */
    public function requireStock()
    {
        $this->assertPropertyIsSet(self::STOCK);

        return $this;
    }

    /**
     * @module Test2
     *
     * @param \ArrayObject|\Generated\Shared\Transfer\FooBarTransfer[] $selfReference
     *
     * @return $this
     */
    public function setSelfReference(ArrayObject $selfReference)
    {
        $this->selfReference = $selfReference;
        $this->modifiedProperties[self::SELF_REFERENCE] = true;

        return $this;
    }

    /**
     * @module Test2
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\FooBarTransfer[]
     */
    public function getSelfReference()
    {
        return $this->selfReference;
    }

    /**
     * @module Test2
     *
     * @param \Generated\Shared\Transfer\FooBarTransfer $selfReference
     *
     * @return $this
     */
    public function addSelfReference(FooBarTransfer $selfReference)
    {
        $this->selfReference[] = $selfReference;
        $this->modifiedProperties[self::SELF_REFERENCE] = true;

        return $this;
    }

    /**
     * @module Test2
     *
     * @return $this
     */
    public function requireSelfReference()
    {
        $this->assertCollectionPropertyIsSet(self::SELF_REFERENCE);

        return $this;
    }
}
