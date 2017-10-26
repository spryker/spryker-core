<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Generated\Shared\Transfer;

use ArrayObject;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

/**
 * !!! THIS FILE IS AUTO-GENERATED, EVERY CHANGE WILL BE LOST WITH THE NEXT RUN OF TRANSFER GENERATOR
 * !!! DO NOT CHANGE ANYTHING IN THIS FILE
 */
class GeneratedTransfer extends AbstractTransfer
{
    const TEST_STRING = 'testString';

    const TEST_STRING_ARRAY = 'testStringArray';

    const TEST_INT = 'testInt';

    const TEST_INT_ARRAY = 'testIntArray';

    const TEST_BOOL = 'testBool';

    const TEST_BOOL_ARRAY = 'testBoolArray';

    const TEST_ARRAY = 'testArray';

    const TEST_TRANSFER = 'testTransfer';

    const TEST_TRANSFERS = 'testTransfers';

    /**
     * @var string
     */
    protected $testString;

    /**
     * @var string[]
     */
    protected $testStringArray;

    /**
     * @var int
     */
    protected $testInt;

    /**
     * @var int[]
     */
    protected $testIntArray;

    /**
     * @var bool
     */
    protected $testBool;

    /**
     * @var bool[]
     */
    protected $testBoolArray;

    /**
     * @var array
     */
    protected $testArray = [];

    /**
     * @var \Generated\Shared\Transfer\GeneratedTransfer
     */
    protected $testTransfer;

    /**
     * @var \ArrayObject|\Generated\Shared\Transfer\GeneratedTransfer[]
     */
    protected $testTransfers;

    /**
     * @var array
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
    ];

    /**
     * @var array
     */
    protected $transferMetadata = [
        self::TEST_STRING => [
            'type' => 'string',
            'name_underscore' => 'test_string',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TEST_STRING_ARRAY => [
            'type' => 'string[]',
            'name_underscore' => 'test_string_array',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TEST_INT => [
            'type' => 'int',
            'name_underscore' => 'test_int',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TEST_INT_ARRAY => [
            'type' => 'int[]',
            'name_underscore' => 'test_int_array',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TEST_BOOL => [
            'type' => 'bool',
            'name_underscore' => 'test_bool',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TEST_BOOL_ARRAY => [
            'type' => 'bool[]',
            'name_underscore' => 'test_bool_array',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TEST_ARRAY => [
            'type' => 'array',
            'name_underscore' => 'test_array',
            'is_collection' => false,
            'is_transfer' => false,
        ],
        self::TEST_TRANSFER => [
            'type' => 'Generated\Shared\Transfer\GeneratedTransfer',
            'name_underscore' => 'test_transfer',
            'is_collection' => false,
            'is_transfer' => true,
        ],
        self::TEST_TRANSFERS => [
            'type' => 'Generated\Shared\Transfer\GeneratedTransfer',
            'name_underscore' => 'test_transfers',
            'is_collection' => true,
            'is_transfer' => true,
        ],
    ];

    /**
     * @module Test
     *
     * @param string $testString
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
     * @return string
     */
    public function getTestString()
    {
        return $this->testString;
    }

    /**
     * @module Test
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
     * @param int $testInt
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
     * @return int
     */
    public function getTestInt()
    {
        return $this->testInt;
    }

    /**
     * @module Test
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
     * @param bool $testBool
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
     * @return bool
     */
    public function getTestBool()
    {
        return $this->testBool;
    }

    /**
     * @module Test
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
     * @param array $testArray
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
     * @return \Generated\Shared\Transfer\GeneratedTransfer
     */
    public function getTestTransfer()
    {
        return $this->testTransfer;
    }

    /**
     * @module Test
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
     * @param \ArrayObject|\Generated\Shared\Transfer\GeneratedTransfer[] $testTransfers
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
     * @return \ArrayObject|\Generated\Shared\Transfer\GeneratedTransfer[]
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
     * @return $this
     */
    public function requireTestTransfers()
    {
        $this->assertCollectionPropertyIsSet(self::TEST_TRANSFERS);

        return $this;
    }
}
