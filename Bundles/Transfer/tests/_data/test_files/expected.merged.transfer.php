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
     * @return string
     */
    public function getNameOrFail()
    {
        if ($this->name === null) {
            $this->throwNullValueException(static::NAME);
        }

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
     * @return int
     */
    public function getBlaOrFail()
    {
        if ($this->bla === null) {
            $this->throwNullValueException(static::BLA);
        }

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
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getStockOrFail()
    {
        if ($this->stock === null) {
            $this->throwNullValueException(static::STOCK);
        }

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

    /**
     * @param array $data
     * @param bool $ignoreMissingProperty
     * @return FooBarTransfer
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'name':
                case 'bla':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;
                    break;
                case 'selfReference':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
                    $this->modifiedProperties[$normalizedPropertyName] = true;
                    break;
                case 'stock':
                    $this->assignValueObject($normalizedPropertyName, $value);
                    break;
                default:
                    if (!$ignoreMissingProperty) {
                        throw new \InvalidArgumentException(sprintf('Missing property `%s` in `%s`', $property, static::class));
                    }
            }
        }

        return $this;
    }

    /**
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false)
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayRecursiveCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->modifiedToArrayNotRecursiveNotCamelCased();
        }
    }

    /**
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    public function toArray($isRecursive = true, $camelCasedKeys = false)
    {
        if ($isRecursive && !$camelCasedKeys) {
            return $this->toArrayRecursiveNotCamelCased();
        }
        if ($isRecursive && $camelCasedKeys) {
            return $this->toArrayRecursiveCamelCased();
        }
        if (!$isRecursive && !$camelCasedKeys) {
            return $this->toArrayNotRecursiveNotCamelCased();
        }
        if (!$isRecursive && $camelCasedKeys) {
            return $this->toArrayNotRecursiveCamelCased();
        }
    }

    /**
    * @param mixed $value
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys)
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->modifiedToArray($isRecursive, $camelCasedKeys);
                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
    * @param mixed $value
    * @param bool $isRecursive
    * @param bool $camelCasedKeys
    * @return array
    */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys)
    {
        $result = [];
        foreach ($value as $elementKey => $arrayElement) {
            if ($arrayElement instanceof AbstractTransfer) {
                $result[$elementKey] = $arrayElement->toArray($isRecursive, $camelCasedKeys);
                continue;
            }
            $result[$elementKey] = $arrayElement;
        }

        return $result;
    }

    /**
    * @return array
    */
    public function modifiedToArrayRecursiveCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, true);
                continue;
            }
            switch ($property) {
                case 'name':
                case 'bla':
                case 'stock':
                    $values[$arrayKey] = $value;
                    break;
                case 'selfReference':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, true) : $value;
                    break;
            }
        }

        return $values;
    }

    /**
    * @return array
    */
    public function modifiedToArrayRecursiveNotCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            if ($value instanceof AbstractTransfer) {
                $values[$arrayKey] = $value->modifiedToArray(true, false);
                continue;
            }
            switch ($property) {
                case 'name':
                case 'bla':
                case 'stock':
                    $values[$arrayKey] = $value;
                    break;
                case 'selfReference':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, false) : $value;
                    break;
            }
        }

        return $values;
    }

    /**
    * @return array
    */
    public function modifiedToArrayNotRecursiveNotCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $this->transferMetadata[$property]['name_underscore'];

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
    * @return array
    */
    public function modifiedToArrayNotRecursiveCamelCased()
    {
        $values = [];
        foreach ($this->modifiedProperties as $property => $_) {
            $value = $this->$property;

            $arrayKey = $property;

            $values[$arrayKey] = $value;
        }

        return $values;
    }

    /**
    * @return void
    */
    protected function initCollectionProperties()
    {
        $this->selfReference = $this->selfReference ?: new ArrayObject();
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveCamelCased()
    {
        return [
            'name' => $this->name,
            'bla' => $this->bla,
            'selfReference' => $this->selfReference,
            'stock' => $this->stock,
        ];
    }

    /**
    * @return array
    */
    public function toArrayNotRecursiveNotCamelCased()
    {
        return [
            'name' => $this->name,
            'bla' => $this->bla,
            'self_reference' => $this->selfReference,
            'stock' => $this->stock,
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveNotCamelCased()
    {
        return [
            'name' => $this->name instanceof AbstractTransfer ? $this->name->toArray(true, false) : $this->name,
            'bla' => $this->bla instanceof AbstractTransfer ? $this->bla->toArray(true, false) : $this->bla,
            'self_reference' => $this->selfReference instanceof AbstractTransfer ? $this->selfReference->toArray(true, false) : $this->addValuesToCollection($this->selfReference, true, false),
            'stock' => $this->stock,
        ];
    }

    /**
    * @return array
    */
    public function toArrayRecursiveCamelCased()
    {
        return [
            'name' => $this->name instanceof AbstractTransfer ? $this->name->toArray(true, true) : $this->name,
            'bla' => $this->bla instanceof AbstractTransfer ? $this->bla->toArray(true, true) : $this->bla,
            'selfReference' => $this->selfReference instanceof AbstractTransfer ? $this->selfReference->toArray(true, true) : $this->addValuesToCollection($this->selfReference, true, true),
            'stock' => $this->stock,
        ];
    }
}
