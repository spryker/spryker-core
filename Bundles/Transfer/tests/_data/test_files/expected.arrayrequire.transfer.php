<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Kernel\Transfer\AbstractAttributesTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class GeneratedTransfer extends AbstractTransfer
{
    /**
     * @var string
     */
    public const TEST_STRING = 'testString';

    /**
     * @var string
     */
    public const TEST_STRING_ARRAY = 'testStringArray';

    /**
     * @var string
     */
    public const TEST_INT = 'testInt';

    /**
     * @var string
     */
    public const TEST_INT_ARRAY = 'testIntArray';

    /**
     * @var string
     */
    public const TEST_BOOL = 'testBool';

    /**
     * @var string
     */
    public const TEST_BOOL_ARRAY = 'testBoolArray';

    /**
     * @var string
     */
    public const TEST_ARRAY = 'testArray';

    /**
     * @var string
     */
    public const TEST_TRANSFER = 'testTransfer';

    /**
     * @var string
     */
    public const TEST_TRANSFERS = 'testTransfers';

    /**
     * @var string
     */
    public const TEST_TRANSFER_STRICT = 'testTransferStrict';

    /**
     * @var string
     */
    public const TEST_DECIMAL = 'testDecimal';

    /**
     * @var string
     */
    public const ASSOCIATIVE_NESTED_TRANSFERS = 'associativeNestedTransfers';

    /**
     * @var string
     */
    public const ABSTRACT_ATTRIBUTES = 'abstractAttributes';

    /**
     * @var string|null
     */
    protected $testString;

    /**
     * @var string[]
     */
    protected $testStringArray = [];

    /**
     * @var int|null
     */
    protected $testInt;

    /**
     * @var int[]
     */
    protected $testIntArray = [];

    /**
     * @var bool|null
     */
    protected $testBool;

    /**
     * @var bool[]
     */
    protected $testBoolArray = [];

    /**
     * @var array
     */
    protected $testArray = [];

    /**
     * @var \Generated\Shared\Transfer\GeneratedTransfer|null
     */
    protected $testTransfer;

    /**
     * @var \ArrayObject<\Generated\Shared\Transfer\GeneratedTransfer>
     */
    protected $testTransfers;

    /**
     * @var \Generated\Shared\Transfer\GeneratedTransfer|null
     */
    protected $testTransferStrict;

    /**
     * @var \Spryker\DecimalObject\Decimal|null
     */
    protected $testDecimal;

    /**
     * @var \ArrayObject<\Generated\Shared\Transfer\GeneratedNestedTransfer>
     */
    protected $associativeNestedTransfers;

    /**
     * @var \Spryker\Shared\Kernel\Transfer\AbstractTransfer|\null
     */
    protected $abstractAttributes;

    /**
     * @var array<string, string>
     */
    protected $transferPropertyNameMap = [
        'test_string' => 'testString',
        'testString' => 'testString',
        'TestString' => 'testString',
        'test_string_array' => 'testStringArray',
        'testStringArray' => 'testStringArray',
        'TestStringArray' => 'testStringArray',
        'test_int' => 'testInt',
        'testInt' => 'testInt',
        'TestInt' => 'testInt',
        'test_int_array' => 'testIntArray',
        'testIntArray' => 'testIntArray',
        'TestIntArray' => 'testIntArray',
        'test_bool' => 'testBool',
        'testBool' => 'testBool',
        'TestBool' => 'testBool',
        'test_bool_array' => 'testBoolArray',
        'testBoolArray' => 'testBoolArray',
        'TestBoolArray' => 'testBoolArray',
        'test_array' => 'testArray',
        'testArray' => 'testArray',
        'TestArray' => 'testArray',
        'test_transfer' => 'testTransfer',
        'testTransfer' => 'testTransfer',
        'TestTransfer' => 'testTransfer',
        'test_transfers' => 'testTransfers',
        'testTransfers' => 'testTransfers',
        'TestTransfers' => 'testTransfers',
        'test_transfer_strict' => 'testTransferStrict',
        'testTransferStrict' => 'testTransferStrict',
        'TestTransferStrict' => 'testTransferStrict',
        'test_decimal' => 'testDecimal',
        'testDecimal' => 'testDecimal',
        'TestDecimal' => 'testDecimal',
        'associative_nested_transfers' => 'associativeNestedTransfers',
        'associativeNestedTransfers' => 'associativeNestedTransfers',
        'AssociativeNestedTransfers' => 'associativeNestedTransfers',
        'abstract_attributes' => 'abstractAttributes',
        'abstractAttributes' => 'abstractAttributes',
        'AbstractAttributes' => 'abstractAttributes',
    ];

    /**
     * @var array<string, array<string, mixed>>
     */
    protected $transferMetadata = [
        self::TEST_STRING => [
            'type' => 'string',
            'type_shim' => null,
            'name_underscore' => 'test_string',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::TEST_STRING_ARRAY => [
            'type' => 'string[]',
            'type_shim' => null,
            'name_underscore' => 'test_string_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => true,
        ],
        self::TEST_INT => [
            'type' => 'int',
            'type_shim' => null,
            'name_underscore' => 'test_int',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::TEST_INT_ARRAY => [
            'type' => 'int[]',
            'type_shim' => null,
            'name_underscore' => 'test_int_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => true,
        ],
        self::TEST_BOOL => [
            'type' => 'bool',
            'type_shim' => null,
            'name_underscore' => 'test_bool',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::TEST_BOOL_ARRAY => [
            'type' => 'bool[]',
            'type_shim' => null,
            'name_underscore' => 'test_bool_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => true,
        ],
        self::TEST_ARRAY => [
            'type' => 'array',
            'type_shim' => null,
            'name_underscore' => 'test_array',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => true,
        ],
        self::TEST_TRANSFER => [
            'type' => 'Generated\Shared\Transfer\GeneratedTransfer',
            'type_shim' => null,
            'name_underscore' => 'test_transfer',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::TEST_TRANSFERS => [
            'type' => 'Generated\Shared\Transfer\GeneratedTransfer',
            'type_shim' => null,
            'name_underscore' => 'test_transfers',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::TEST_TRANSFER_STRICT => [
            'type' => 'Generated\Shared\Transfer\GeneratedTransfer',
            'type_shim' => null,
            'name_underscore' => 'test_transfer_strict',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => true,
            'is_primitive_array' => false,
        ],
        self::TEST_DECIMAL => [
            'type' => 'Spryker\DecimalObject\Decimal',
            'type_shim' => null,
            'name_underscore' => 'test_decimal',
            'is_collection' => false,
            'is_transfer' => false,
            'is_value_object' => true,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::ASSOCIATIVE_NESTED_TRANSFERS => [
            'type' => 'Generated\Shared\Transfer\GeneratedNestedTransfer',
            'type_shim' => null,
            'name_underscore' => 'associative_nested_transfers',
            'is_collection' => true,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => true,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
        self::ABSTRACT_ATTRIBUTES => [
            'type' => 'Spryker\Shared\Kernel\Transfer\AbstractAttributesTransfer',
            'type_shim' => null,
            'name_underscore' => 'abstract_attributes',
            'is_collection' => false,
            'is_transfer' => true,
            'is_value_object' => false,
            'rest_request_parameter' => 'no',
            'rest_response_parameter' => 'yes',
            'example' => '',
            'description' => '',
            'is_associative' => false,
            'is_nullable' => false,
            'is_strict' => false,
            'is_primitive_array' => false,
        ],
    ];

    /**
     * @module Test
     *
     * @param string|null $testString
     *
     * @return $this
     */
    public function setTestString($testString)
    {
        $this->testString = $testString;
        $this->modifiedProperties[self::TEST_STRING] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string|null
     */
    public function getTestString()
    {
        return $this->testString;
    }

    /**
     * @module Test
     *
     * @param string|null $testString
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTestStringOrFail($testString)
    {
        if ($testString === null) {
            $this->throwNullValueException(static::TEST_STRING);
        }

        return $this->setTestString($testString);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return string
     */
    public function getTestStringOrFail()
    {
        if ($this->testString === null) {
            $this->throwNullValueException(static::TEST_STRING);
        }

        return $this->testString;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestString()
    {
        $this->assertPropertyIsSet(self::TEST_STRING);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string[]|null $testStringArray
     *
     * @return $this
     */
    public function setTestStringArray(array $testStringArray = null)
    {
        if ($testStringArray === null) {
            $testStringArray = [];
        }

        $this->testStringArray = $testStringArray;
        $this->modifiedProperties[self::TEST_STRING_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return string[]
     */
    public function getTestStringArray()
    {
        return $this->testStringArray;
    }

    /**
     * @module Test
     *
     * @param string $testStringArray
     *
     * @return $this
     */
    public function addTestStringArray($testStringArray)
    {
        $this->testStringArray[] = $testStringArray;
        $this->modifiedProperties[self::TEST_STRING_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestStringArray()
    {
        $this->assertPropertyIsSet(self::TEST_STRING_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param int|null $testInt
     *
     * @return $this
     */
    public function setTestInt($testInt)
    {
        $this->testInt = $testInt;
        $this->modifiedProperties[self::TEST_INT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return int|null
     */
    public function getTestInt()
    {
        return $this->testInt;
    }

    /**
     * @module Test
     *
     * @param int|null $testInt
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTestIntOrFail($testInt)
    {
        if ($testInt === null) {
            $this->throwNullValueException(static::TEST_INT);
        }

        return $this->setTestInt($testInt);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return int
     */
    public function getTestIntOrFail()
    {
        if ($this->testInt === null) {
            $this->throwNullValueException(static::TEST_INT);
        }

        return $this->testInt;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestInt()
    {
        $this->assertPropertyIsSet(self::TEST_INT);

        return $this;
    }

    /**
     * @module Test
     *
     * @param int[]|null $testIntArray
     *
     * @return $this
     */
    public function setTestIntArray(array $testIntArray = null)
    {
        if ($testIntArray === null) {
            $testIntArray = [];
        }

        $this->testIntArray = $testIntArray;
        $this->modifiedProperties[self::TEST_INT_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return int[]
     */
    public function getTestIntArray()
    {
        return $this->testIntArray;
    }

    /**
     * @module Test
     *
     * @param int $testIntArray
     *
     * @return $this
     */
    public function addTestIntArray($testIntArray)
    {
        $this->testIntArray[] = $testIntArray;
        $this->modifiedProperties[self::TEST_INT_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestIntArray()
    {
        $this->assertPropertyIsSet(self::TEST_INT_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param bool|null $testBool
     *
     * @return $this
     */
    public function setTestBool($testBool)
    {
        $this->testBool = $testBool;
        $this->modifiedProperties[self::TEST_BOOL] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return bool|null
     */
    public function getTestBool()
    {
        return $this->testBool;
    }

    /**
     * @module Test
     *
     * @param bool|null $testBool
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTestBoolOrFail($testBool)
    {
        if ($testBool === null) {
            $this->throwNullValueException(static::TEST_BOOL);
        }

        return $this->setTestBool($testBool);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return bool
     */
    public function getTestBoolOrFail()
    {
        if ($this->testBool === null) {
            $this->throwNullValueException(static::TEST_BOOL);
        }

        return $this->testBool;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestBool()
    {
        $this->assertPropertyIsSet(self::TEST_BOOL);

        return $this;
    }

    /**
     * @module Test
     *
     * @param bool[]|null $testBoolArray
     *
     * @return $this
     */
    public function setTestBoolArray(array $testBoolArray = null)
    {
        if ($testBoolArray === null) {
            $testBoolArray = [];
        }

        $this->testBoolArray = $testBoolArray;
        $this->modifiedProperties[self::TEST_BOOL_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return bool[]
     */
    public function getTestBoolArray()
    {
        return $this->testBoolArray;
    }

    /**
     * @module Test
     *
     * @param bool $testBoolArray
     *
     * @return $this
     */
    public function addTestBoolArray($testBoolArray)
    {
        $this->testBoolArray[] = $testBoolArray;
        $this->modifiedProperties[self::TEST_BOOL_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestBoolArray()
    {
        $this->assertPropertyIsSet(self::TEST_BOOL_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param array|null $testArray
     *
     * @return $this
     */
    public function setTestArray(array $testArray = null)
    {
        if ($testArray === null) {
            $testArray = [];
        }

        $this->testArray = $testArray;
        $this->modifiedProperties[self::TEST_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return array
     */
    public function getTestArray()
    {
        return $this->testArray;
    }

    /**
     * @module Test
     *
     * @param mixed $testArray
     *
     * @return $this
     */
    public function addTestArray($testArray)
    {
        $this->testArray[] = $testArray;
        $this->modifiedProperties[self::TEST_ARRAY] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestArray()
    {
        $this->assertPropertyIsSet(self::TEST_ARRAY);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer|null $testTransfer
     *
     * @return $this
     */
    public function setTestTransfer(GeneratedTransfer $testTransfer = null)
    {
        $this->testTransfer = $testTransfer;
        $this->modifiedProperties[self::TEST_TRANSFER] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\GeneratedTransfer|null
     */
    public function getTestTransfer()
    {
        return $this->testTransfer;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $testTransfer
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTestTransferOrFail(GeneratedTransfer $testTransfer)
    {
        return $this->setTestTransfer($testTransfer);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Generated\Shared\Transfer\GeneratedTransfer
     */
    public function getTestTransferOrFail()
    {
        if ($this->testTransfer === null) {
            $this->throwNullValueException(static::TEST_TRANSFER);
        }

        return $this->testTransfer;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestTransfer()
    {
        $this->assertPropertyIsSet(self::TEST_TRANSFER);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \ArrayObject<\Generated\Shared\Transfer\GeneratedTransfer> $testTransfers
     *
     * @return $this
     */
    public function setTestTransfers(ArrayObject $testTransfers)
    {
        $this->testTransfers = $testTransfers;
        $this->modifiedProperties[self::TEST_TRANSFERS] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\GeneratedTransfer>
     */
    public function getTestTransfers()
    {
        return $this->testTransfers;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $transfer
     *
     * @return $this
     */
    public function addTransfer(GeneratedTransfer $transfer)
    {
        $this->testTransfers[] = $transfer;
        $this->modifiedProperties[self::TEST_TRANSFERS] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestTransfers()
    {
        $this->assertCollectionPropertyIsSet(self::TEST_TRANSFERS);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer|null $testTransferStrict
     *
     * @return $this
     */
    public function setTestTransferStrict(?GeneratedTransfer $testTransferStrict = null)
    {
        $this->testTransferStrict = $testTransferStrict;
        $this->modifiedProperties[self::TEST_TRANSFER_STRICT] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Generated\Shared\Transfer\GeneratedTransfer|null
     */
    public function getTestTransferStrict(): ?GeneratedTransfer
    {
        return $this->testTransferStrict;
    }

    /**
     * @module Test
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $testTransferStrict
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTestTransferStrictOrFail(GeneratedTransfer $testTransferStrict)
    {
        return $this->setTestTransferStrict($testTransferStrict);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Generated\Shared\Transfer\GeneratedTransfer
     */
    public function getTestTransferStrictOrFail(): GeneratedTransfer
    {
        if ($this->testTransferStrict === null) {
            $this->throwNullValueException(static::TEST_TRANSFER_STRICT);
        }

        return $this->testTransferStrict;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestTransferStrict()
    {
        $this->assertPropertyIsSet(self::TEST_TRANSFER_STRICT);

        return $this;
    }

    /**
     * @module Test
     *
     * @param string|int|float|\Spryker\DecimalObject\Decimal|null $testDecimal
     *
     * @return $this
     */
    public function setTestDecimal($testDecimal = null)
    {
        if ($testDecimal !== null && !$testDecimal instanceof Decimal) {
            $testDecimal = new Decimal($testDecimal);
        }

        $this->testDecimal = $testDecimal;
        $this->modifiedProperties[self::TEST_DECIMAL] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Spryker\DecimalObject\Decimal|null
     */
    public function getTestDecimal()
    {
        return $this->testDecimal;
    }

    /**
     * @module Test
     *
     * @param string|int|float|\Spryker\DecimalObject\Decimal $testDecimal
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setTestDecimalOrFail($testDecimal)
    {
        if ($testDecimal === null) {
            $this->throwNullValueException(static::TEST_DECIMAL);
        }

        return $this->setTestDecimal($testDecimal);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Spryker\DecimalObject\Decimal
     */
    public function getTestDecimalOrFail()
    {
        if ($this->testDecimal === null) {
            $this->throwNullValueException(static::TEST_DECIMAL);
        }

        return $this->testDecimal;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireTestDecimal()
    {
        $this->assertPropertyIsSet(self::TEST_DECIMAL);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \ArrayObject<\Generated\Shared\Transfer\GeneratedNestedTransfer> $associativeNestedTransfers
     *
     * @return $this
     */
    public function setAssociativeNestedTransfers(ArrayObject $associativeNestedTransfers)
    {
        $this->associativeNestedTransfers = $associativeNestedTransfers;
        $this->modifiedProperties[self::ASSOCIATIVE_NESTED_TRANSFERS] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \ArrayObject<\Generated\Shared\Transfer\GeneratedNestedTransfer>
     */
    public function getAssociativeNestedTransfers()
    {
        return $this->associativeNestedTransfers;
    }

    /**
     * @module Test
     *
     * @param string|int $associativeNestedTransferKey
     * @param \Generated\Shared\Transfer\GeneratedNestedTransfer $associativeNestedTransferValue
     *
     * @return $this
     */
    public function addAssociativeNestedTransfer($associativeNestedTransferKey, GeneratedNestedTransfer $associativeNestedTransferValue)
    {
        $this->associativeNestedTransfers[$associativeNestedTransferKey] = $associativeNestedTransferValue;
        $this->modifiedProperties[self::ASSOCIATIVE_NESTED_TRANSFERS] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireAssociativeNestedTransfers()
    {
        $this->assertCollectionPropertyIsSet(self::ASSOCIATIVE_NESTED_TRANSFERS);

        return $this;
    }

    /**
     * @module Test
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $abstractAttributes
     *
     * @return $this
     */
    public function setAbstractAttributes(AbstractTransfer $abstractAttributes = null)
    {
        if ($abstractAttributes !== null && !$abstractAttributes instanceof AbstractAttributesTransfer) {
            $abstractAttributes = (new AbstractAttributesTransfer())
                ->setAbstractAttributesType(get_class($abstractAttributes))
                ->fromArray($abstractAttributes->toArray(), true);
        }

        $this->abstractAttributes = $abstractAttributes;
        $this->modifiedProperties[self::ABSTRACT_ATTRIBUTES] = true;

        return $this;
    }

    /**
     * @module Test
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function getAbstractAttributes()
    {
        return $this->abstractAttributes ? $this->abstractAttributes->getValueTransfer() : null;
    }

    /**
     * @module Test
     *
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $abstractAttributes
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return $this
     */
    public function setAbstractAttributesOrFail(AbstractTransfer $abstractAttributes)
    {
        return $this->setAbstractAttributes($abstractAttributes);
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\NullValueException
     *
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function getAbstractAttributesOrFail()
    {
        if ($this->abstractAttributes === null) {
            $this->throwNullValueException(static::ABSTRACT_ATTRIBUTES);
        }

        return $this->abstractAttributes ? $this->abstractAttributes->getValueTransfer() : null;
    }

    /**
     * @module Test
     *
     * @throws \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @return $this
     */
    public function requireAbstractAttributes()
    {
        $this->assertPropertyIsSet(self::ABSTRACT_ATTRIBUTES);

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     * @param bool $ignoreMissingProperty
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function fromArray(array $data, $ignoreMissingProperty = false)
    {
        foreach ($data as $property => $value) {
            $normalizedPropertyName = $this->transferPropertyNameMap[$property] ?? null;

            switch ($normalizedPropertyName) {
                case 'testString':
                case 'testStringArray':
                case 'testInt':
                case 'testIntArray':
                case 'testBool':
                case 'testBoolArray':
                case 'testArray':
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'abstractAttributes':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\AbstractAttributesTransfer $value */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }

                    if ($value !== null && $this->isPropertyStrict($normalizedPropertyName)) {
                        $this->assertInstanceOfTransfer($normalizedPropertyName, $value);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'testTransfer':
                case 'testTransferStrict':
                    if (is_array($value)) {
                        $type = $this->transferMetadata[$normalizedPropertyName]['type'];
                        /** @var \Spryker\Shared\Kernel\Transfer\TransferInterface $value */
                        $value = (new $type())->fromArray($value, $ignoreMissingProperty);
                    }

                    if ($value !== null && $this->isPropertyStrict($normalizedPropertyName)) {
                        $this->assertInstanceOfTransfer($normalizedPropertyName, $value);
                    }
                    $this->$normalizedPropertyName = $value;
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'testTransfers':
                case 'associativeNestedTransfers':
                    $elementType = $this->transferMetadata[$normalizedPropertyName]['type'];
                    $this->$normalizedPropertyName = $this->processArrayObject($elementType, $value, $ignoreMissingProperty);
                    $this->modifiedProperties[$normalizedPropertyName] = true;

                    break;
                case 'testDecimal':
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
     *
     * @return array<string, mixed>
     */
    public function modifiedToArray($isRecursive = true, $camelCasedKeys = false): array
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
     *
     * @return array<string, mixed>
     */
    public function toArray($isRecursive = true, $camelCasedKeys = false): array
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
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollectionModified($value, $isRecursive, $camelCasedKeys): array
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
     * @param array<string, mixed>|\ArrayObject<string, mixed> $value
     * @param bool $isRecursive
     * @param bool $camelCasedKeys
     *
     * @return array<string, mixed>
     */
    protected function addValuesToCollection($value, $isRecursive, $camelCasedKeys): array
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
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveCamelCased(): array
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
                case 'testString':
                case 'testStringArray':
                case 'testInt':
                case 'testIntArray':
                case 'testBool':
                case 'testBoolArray':
                case 'testArray':
                case 'testDecimal':
                    $values[$arrayKey] = $value;

                    break;
                case 'testTransfer':
                case 'testTransferStrict':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, true) : $value;

                    break;
                case 'testTransfers':
                case 'associativeNestedTransfers':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, true) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayRecursiveNotCamelCased(): array
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
                case 'testString':
                case 'testStringArray':
                case 'testInt':
                case 'testIntArray':
                case 'testBool':
                case 'testBoolArray':
                case 'testArray':
                case 'testDecimal':
                    $values[$arrayKey] = $value;

                    break;
                case 'testTransfer':
                case 'testTransferStrict':
                    $values[$arrayKey] = $value instanceof AbstractTransfer ? $value->modifiedToArray(true, false) : $value;

                    break;
                case 'testTransfers':
                case 'associativeNestedTransfers':
                    $values[$arrayKey] = $value ? $this->addValuesToCollectionModified($value, true, false) : $value;

                    break;
            }
        }

        return $values;
    }

    /**
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveNotCamelCased(): array
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
     * @return array<string, mixed>
     */
    public function modifiedToArrayNotRecursiveCamelCased(): array
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
    protected function initCollectionProperties(): void
    {
        $this->testTransfers = $this->testTransfers ?: new ArrayObject();
        $this->associativeNestedTransfers = $this->associativeNestedTransfers ?: new ArrayObject();
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveCamelCased(): array
    {
        return [
            'testString' => $this->testString,
            'testStringArray' => $this->testStringArray,
            'testInt' => $this->testInt,
            'testIntArray' => $this->testIntArray,
            'testBool' => $this->testBool,
            'testBoolArray' => $this->testBoolArray,
            'testArray' => $this->testArray,
            'abstractAttributes' => $this->abstractAttributes,
            'testTransfer' => $this->testTransfer,
            'testTransferStrict' => $this->testTransferStrict,
            'testTransfers' => $this->testTransfers,
            'associativeNestedTransfers' => $this->associativeNestedTransfers,
            'testDecimal' => $this->testDecimal,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayNotRecursiveNotCamelCased(): array
    {
        return [
            'test_string' => $this->testString,
            'test_string_array' => $this->testStringArray,
            'test_int' => $this->testInt,
            'test_int_array' => $this->testIntArray,
            'test_bool' => $this->testBool,
            'test_bool_array' => $this->testBoolArray,
            'test_array' => $this->testArray,
            'abstract_attributes' => $this->abstractAttributes,
            'test_transfer' => $this->testTransfer,
            'test_transfer_strict' => $this->testTransferStrict,
            'test_transfers' => $this->testTransfers,
            'associative_nested_transfers' => $this->associativeNestedTransfers,
            'test_decimal' => $this->testDecimal,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveNotCamelCased(): array
    {
        return [
            'test_string' => $this->testString instanceof AbstractTransfer ? $this->testString->toArray(true, false) : $this->testString,
            'test_string_array' => $this->testStringArray instanceof AbstractTransfer ? $this->testStringArray->toArray(true, false) : $this->testStringArray,
            'test_int' => $this->testInt instanceof AbstractTransfer ? $this->testInt->toArray(true, false) : $this->testInt,
            'test_int_array' => $this->testIntArray instanceof AbstractTransfer ? $this->testIntArray->toArray(true, false) : $this->testIntArray,
            'test_bool' => $this->testBool instanceof AbstractTransfer ? $this->testBool->toArray(true, false) : $this->testBool,
            'test_bool_array' => $this->testBoolArray instanceof AbstractTransfer ? $this->testBoolArray->toArray(true, false) : $this->testBoolArray,
            'test_array' => $this->testArray instanceof AbstractTransfer ? $this->testArray->toArray(true, false) : $this->testArray,
            'abstract_attributes' => $this->abstractAttributes instanceof AbstractTransfer ? $this->abstractAttributes->toArray(true, false) : $this->abstractAttributes,
            'test_transfer' => $this->testTransfer instanceof AbstractTransfer ? $this->testTransfer->toArray(true, false) : $this->testTransfer,
            'test_transfer_strict' => $this->testTransferStrict instanceof AbstractTransfer ? $this->testTransferStrict->toArray(true, false) : $this->testTransferStrict,
            'test_transfers' => $this->testTransfers instanceof AbstractTransfer ? $this->testTransfers->toArray(true, false) : $this->addValuesToCollection($this->testTransfers, true, false),
            'associative_nested_transfers' => $this->associativeNestedTransfers instanceof AbstractTransfer ? $this->associativeNestedTransfers->toArray(true, false) : $this->addValuesToCollection($this->associativeNestedTransfers, true, false),
            'test_decimal' => $this->testDecimal,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArrayRecursiveCamelCased(): array
    {
        return [
            'testString' => $this->testString instanceof AbstractTransfer ? $this->testString->toArray(true, true) : $this->testString,
            'testStringArray' => $this->testStringArray instanceof AbstractTransfer ? $this->testStringArray->toArray(true, true) : $this->testStringArray,
            'testInt' => $this->testInt instanceof AbstractTransfer ? $this->testInt->toArray(true, true) : $this->testInt,
            'testIntArray' => $this->testIntArray instanceof AbstractTransfer ? $this->testIntArray->toArray(true, true) : $this->testIntArray,
            'testBool' => $this->testBool instanceof AbstractTransfer ? $this->testBool->toArray(true, true) : $this->testBool,
            'testBoolArray' => $this->testBoolArray instanceof AbstractTransfer ? $this->testBoolArray->toArray(true, true) : $this->testBoolArray,
            'testArray' => $this->testArray instanceof AbstractTransfer ? $this->testArray->toArray(true, true) : $this->testArray,
            'abstractAttributes' => $this->abstractAttributes instanceof AbstractTransfer ? $this->abstractAttributes->toArray(true, true) : $this->abstractAttributes,
            'testTransfer' => $this->testTransfer instanceof AbstractTransfer ? $this->testTransfer->toArray(true, true) : $this->testTransfer,
            'testTransferStrict' => $this->testTransferStrict instanceof AbstractTransfer ? $this->testTransferStrict->toArray(true, true) : $this->testTransferStrict,
            'testTransfers' => $this->testTransfers instanceof AbstractTransfer ? $this->testTransfers->toArray(true, true) : $this->addValuesToCollection($this->testTransfers, true, true),
            'associativeNestedTransfers' => $this->associativeNestedTransfers instanceof AbstractTransfer ? $this->associativeNestedTransfers->toArray(true, true) : $this->addValuesToCollection($this->associativeNestedTransfers, true, true),
            'testDecimal' => $this->testDecimal,
        ];
    }
}
