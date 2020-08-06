<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GeneratedNestedTransfer;
use Generated\Shared\Transfer\GeneratedTransfer;
use Spryker\DecimalObject\Decimal;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\GeneratorInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\Business\Model\TransferGenerator;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Console\Logger\ConsoleLogger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group GeneratedTransferTest
 * Add your own group annotations below this line
 */
class GeneratedTransferTest extends Unit
{
    /**
     * @var bool
     */
    protected $isTransferGenerated = false;

    /**
     * @return void
     */
    public function _before(): void
    {
        if (!$this->isTransferGenerated) {
            $this->generateTransfer();
            require_once(static::getTargetDirectory() . 'GeneratedTransfer.php');
            require_once(static::getTargetDirectory() . 'GeneratedNestedTransfer.php');
            $this->isTransferGenerated = true;
        }
    }

    /**
     * @return void
     */
    public function testStringProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->setTestString('string');
        $this->assertSame('string', $generatedTransfer->getTestString());
        $this->assertIsString($generatedTransfer->getTestString());

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_string' => 'string'], $modified);

        $generatedTransfer->requireTestString();

        $generatedTransfer->setTestString(null);
        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_string' => null], $modified);

        $this->expectException(RequiredTransferPropertyException::class);
        $generatedTransfer->requireTestString();
    }

    /**
     * @return void
     */
    public function testStringArrayProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();

        $generatedTransfer->setTestStringArray(['string a', 'string b']);
        $this->assertSame(['string a', 'string b'], $generatedTransfer->getTestStringArray());
        $this->assertIsArray($generatedTransfer->getTestStringArray());

        $generatedTransfer->requireTestStringArray();

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_string_array' => ['string a', 'string b']], $modified);

        $generatedTransfer->setTestStringArray(null);
        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_string_array' => []], $modified);
    }

    /**
     * @return void
     */
    public function testStringArrayPropertyAdd(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Act
        $generatedTransfer->addTestStringArray('string for array');

        // Assert
        $this->assertSame(['string for array'], $generatedTransfer->getTestStringArray());
    }

    /**
     * @return void
     */
    public function testIntProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->setTestInt(100);
        $this->assertSame(100, $generatedTransfer->getTestInt());
        $this->assertIsInt($generatedTransfer->getTestInt());

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_int' => 100], $modified);

        $generatedTransfer->requireTestInt();

        $generatedTransfer->setTestInt(null);
        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_int' => null], $modified);

        $this->expectException(RequiredTransferPropertyException::class);
        $generatedTransfer->requireTestInt();
    }

    /**
     * @return void
     */
    public function testIntArrayProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->setTestIntArray([100, 200]);
        $this->assertSame([100, 200], $generatedTransfer->getTestIntArray());
        $this->assertIsArray($generatedTransfer->getTestIntArray());

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_int_array' => [100, 200]], $modified);

        $generatedTransfer->setTestIntArray(null);
        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_int_array' => []], $modified);
    }

    /**
     * @return void
     */
    public function testIntArrayPropertyAdd(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Act
        $generatedTransfer->addTestIntArray(300);

        // Assert
        $this->assertSame([300], $generatedTransfer->getTestIntArray());
    }

    /**
     * @return void
     */
    public function testBoolProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->setTestBool(true);
        $this->assertSame(true, $generatedTransfer->getTestBool());
        $this->assertIsBool($generatedTransfer->getTestBool());

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_bool' => true], $modified);

        $generatedTransfer->requireTestBool();

        $generatedTransfer->setTestBool(null);
        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_bool' => null], $modified);

        $this->expectException(RequiredTransferPropertyException::class);
        $generatedTransfer->requireTestBool();
    }

    /**
     * @return void
     */
    public function testBoolArrayProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->setTestBoolArray([true, false]);
        $this->assertSame([true, false], $generatedTransfer->getTestBoolArray());
        $this->assertIsArray($generatedTransfer->getTestBoolArray());

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_bool_array' => [true, false]], $modified);

        $generatedTransfer->setTestBoolArray(null);
        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_bool_array' => []], $modified);
    }

    /**
     * @return void
     */
    public function testBoolArrayPropertyAdd(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Act
        $generatedTransfer->addTestBoolArray(true);

        // Assert
        $this->assertSame([true], $generatedTransfer->getTestBoolArray());
    }

    /**
     * @return void
     */
    public function testArrayProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->setTestArray([]);

        $this->assertSame([], $generatedTransfer->getTestArray());
        $this->assertIsArray($generatedTransfer->getTestArray());

        $modified = $generatedTransfer->modifiedToArray();

        $this->assertSame(['test_array' => []], $modified);

        $generatedTransfer->setTestArray(null);
        $modified = $generatedTransfer->modifiedToArray();

        $this->assertSame(['test_array' => []], $modified);

        $generatedTransfer->fromArray([
            'test_array' => null,
        ]);

        $this->assertSame(['test_array' => null], $generatedTransfer->modifiedToArray());
    }

    /**
     * @return void
     */
    public function testTransferProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $innerGeneratedTransfer = new GeneratedTransfer();
        $generatedTransfer->setTestTransfer($innerGeneratedTransfer);
        $this->assertSame($innerGeneratedTransfer, $generatedTransfer->getTestTransfer());
        $this->assertInstanceOf(TransferInterface::class, $generatedTransfer->getTestTransfer());

        $generatedTransfer->requireTestTransfer();

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_transfer' => $innerGeneratedTransfer->modifiedToArray()], $modified);

        $generatedTransfer->setTestTransfer(null);
        $this->expectException(RequiredTransferPropertyException::class);
        $generatedTransfer->requireTestTransfer();
    }

    /**
     * @return void
     */
    public function testTransferCollectionProperty(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $transferCollection = new ArrayObject([
            new GeneratedTransfer(),
        ]);
        $generatedTransfer->setTestTransfers($transferCollection);
        $this->assertSame($transferCollection, $generatedTransfer->getTestTransfers());
        $this->assertInstanceOf(ArrayObject::class, $generatedTransfer->getTestTransfers());

        $generatedTransfer->requireTestTransfers();

        $modified = $generatedTransfer->modifiedToArray();
        $expectedTransferCollection = [
            (new GeneratedTransfer())->modifiedToArray(),
        ];
        $this->assertSame(['test_transfers' => $expectedTransferCollection], $modified);

        $generatedTransfer->setTestTransfers(new ArrayObject());
        $this->expectException(RequiredTransferPropertyException::class);
        $generatedTransfer->requireTestTransfers();
    }

    /**
     * @return void
     */
    public function testTransferCollectionPropertyAdd(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->addTransfer(new GeneratedTransfer());
        $transferCollection = new ArrayObject([
            new GeneratedTransfer(),
        ]);

        $this->assertEquals($transferCollection, $generatedTransfer->getTestTransfers());

        $expectedTransferCollection = [
            (new GeneratedTransfer())->modifiedToArray(),
        ];
        $modified = $generatedTransfer->modifiedToArray();

        $this->assertSame(['test_transfers' => $expectedTransferCollection], $modified);
    }

    /**
     * @return void
     */
    public function testFromArrayWithUnderScoreNames(): void
    {
        // Assign
        $generatedTransferData = [
            'test_string' => 'string',
            'test_string_array' => ['string a', 'string b'],
            'test_int' => 100,
            'test_int_array' => [100, 200],
            'test_bool' => true,
            'test_bool_array' => [true, false],
            'test_transfer' => [
                'test_string' => 'string',
                'test_int' => 100,
                'test_bool' => true,
                'test_array' => [],
            ],
        ];

        $generatedTransfer = new GeneratedTransfer();

        // Act
        $generatedTransfer->fromArray($generatedTransferData);

        // Assert
        $this->assertSame('string', $generatedTransfer->getTestString());
        $this->assertSame(['string a', 'string b'], $generatedTransfer->getTestStringArray());
        $this->assertSame(100, $generatedTransfer->getTestInt());
        $this->assertSame([100, 200], $generatedTransfer->getTestIntArray());
        $this->assertSame(true, $generatedTransfer->getTestBool());
        $this->assertSame([true, false], $generatedTransfer->getTestBoolArray());
        $this->assertSame([true, false], $generatedTransfer->getTestBoolArray());
        $this->assertInstanceOf(GeneratedTransfer::class, $generatedTransfer->getTestTransfer());
    }

    /**
     * @return void
     */
    public function testDecimalPropertyOfDecimalType(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Act
        $generatedTransfer->setTestDecimal(1);

        // Assert
        $this->assertInstanceOf(Decimal::class, $generatedTransfer->getTestDecimal());
        $this->assertTrue($generatedTransfer->getTestDecimal()->equals(1));
    }

    /**
     * @return void
     */
    public function testDecimalPropertyToArray(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Act
        $generatedTransfer->setTestDecimal(1);
        $modified = $generatedTransfer->modifiedToArray();

        // Assert
        $this->assertInstanceOf(Decimal::class, $modified['test_decimal']);
        $this->assertSame(['test_decimal' => $generatedTransfer->getTestDecimal()], $modified);
    }

    /**
     * @return void
     */
    public function testDecimalPropertyFromArray(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Act
        $generatedTransfer->fromArray([
            'test_decimal' => 1.01,
        ]);

        // Assert
        $this->assertInstanceOf(Decimal::class, $generatedTransfer->modifiedToArray()['test_decimal']);
    }

    /**
     * @return void
     */
    public function testDecimalPropertyNullable(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Act
        $generatedTransfer->setTestDecimal(null);
        $modified = $generatedTransfer->modifiedToArray();

        // Assert
        $this->assertSame(['test_decimal' => null], $modified);
    }

    /**
     * @return void
     */
    public function testDecimalPropertyRequire(): void
    {
        $generatedTransfer = new GeneratedTransfer();
        // Assert
        $this->expectException(RequiredTransferPropertyException::class);
        $generatedTransfer->requireTestDecimal();
    }

    /**
     * @return void
     */
    protected function generateTransfer(): void
    {
        $definitionBuilder = $this->getDefinitionBuilder([
            codecept_data_dir('GeneratedTest/'),
        ]);

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'GeneratedTransfer.php');
        $this->assertFileExists($this->getTargetDirectory() . 'GeneratedNestedTransfer.php');
    }

    /**
     * @return array
     */
    public function toArrayProvider(): array
    {
        $input = [
            'test_string' => 'string',
            'test_string_array' => ['string a', 'string b'],
            'test_int' => 100,
            'test_int_array' => [100, 200],
            'test_bool' => true,
            'test_bool_array' => [true, false],
            'test_transfer' => [
                'test_string' => 'string',
                'test_int' => 100,
                'test_bool' => true,
                'test_array' => [],
            ],
        ];
        $underscoreOut = [
            'test_string' => 'string',
            'test_string_array' => ['string a', 'string b'],
            'test_int' => 100,
            'test_int_array' => [100, 200],
            'test_bool' => true,
            'test_bool_array' => [true, false],
        ];
        $camelCaseOut = [
            'testString' => 'string',
            'testStringArray' => ['string a', 'string b'],
            'testInt' => 100,
            'testIntArray' => [100, 200],
            'testBool' => true,
            'testBoolArray' => [true, false],
        ];

        return [
            [
                'isRecursive' => false,
                'camelCase' => false,
                'input' => $input,
                'expected' => array_merge($underscoreOut, [
                    'test_transfer' => function ($value): void {
                        $this->assertInstanceOf(GeneratedTransfer::class, $value);
                    },
                ]),

            ],
            [
                'isRecursive' => true,
                'camelCase' => false,
                'input' => $input,
                'expected' => array_merge($underscoreOut, [
                    'test_transfer' => [
                        'test_string' => 'string',
                        'test_int' => 100,
                        'test_bool' => true,
                        'test_array' => [],
                    ],
                ]),
            ],
            [
                'isRecursive' => false,
                'camelCase' => true,
                'input' => $input,
                'expected' => array_merge($camelCaseOut, [
                    'testTransfer' => function ($value): void {
                        $this->assertInstanceOf(GeneratedTransfer::class, $value);
                    },
                ]),
            ],
            [
                'isRecursive' => true,
                'camelCase' => true,
                'input' => $input,
                'expected' => array_merge($camelCaseOut, [
                    'testTransfer' => [
                        'testString' => 'string',
                        'testInt' => 100,
                        'testBool' => true,
                        'testArray' => [],
                    ],
                ]),
            ],
        ];
    }

    /**
     * @dataProvider toArrayProvider
     *
     * @param bool $isRecursive
     * @param bool $isCamelCase
     * @param array $input
     * @param array $expected
     *
     * @return void
     */
    public function testToArray(bool $isRecursive, bool $isCamelCase, array $input, array $expected): void
    {
        $generatedTransfer = new GeneratedTransfer();
        $generatedTransfer->fromArray($input);

        $this->assertToArrayResult($generatedTransfer->toArray($isRecursive, $isCamelCase), $expected);
        $this->assertToArrayResult($generatedTransfer->modifiedToArray($isRecursive, $isCamelCase), $expected);
    }

    /**
     * @dataProvider associativeCollectionFromArrayProvider
     *
     * @param array $input
     * @param array $expected
     *
     * @return void
     */
    public function testAssociativeCollectionFromArray(array $input, array $expected): void
    {
        // Arrange
        $generatedTransfer = new GeneratedTransfer();

        // Act
        $generatedTransfer->fromArray([
            'associativeNestedTransfers' => $input,
        ]);
        $normalized = $generatedTransfer->toArray(true);

        // Assert
        $this->assertArrayHasKey('associative_nested_transfers', $normalized);
        $this->assertEquals($expected, $normalized['associative_nested_transfers']);
    }

    /**
     * @return void
     */
    public function testAssociativeCollectionAdder(): void
    {
        // Arrange
        $generatedTransfer = new GeneratedTransfer();

        // Act
        $generatedTransfer->addAssociativeNestedTransfer('one', new GeneratedNestedTransfer());
        $normalized = $generatedTransfer->toArray(false);

        // Assert
        $this->assertArrayHasKey('associative_nested_transfers', $normalized);
        $this->assertInstanceOf(ArrayObject::class, $normalized['associative_nested_transfers']);
        $this->assertArrayHasKey('one', $normalized['associative_nested_transfers']);
        $this->assertInstanceOf(GeneratedNestedTransfer::class, $normalized['associative_nested_transfers']['one']);
    }

    /**
     * @return array
     */
    public function associativeCollectionFromArrayProvider(): array
    {
        return [
            'nested with fields' => [
                'input' => [
                    'one' => [
                        'name' => 'one_name',
                    ],
                    'two' => [
                        'name' => 'two_name',
                    ],
                ],
                'expected' => [
                    'one' => [
                        'name' => 'one_name',
                    ],
                    'two' => [
                        'name' => 'two_name',
                    ],
                ],
            ],
            'nested as empty array' => [
                'input' => [
                    'one' => [],
                    'two' => [],
                ],
                'expected' => [],
            ],
            'nested as nulls' => [
                'input' => [
                    'one' => null,
                    'two' => null,
                ],
                'expected' => [
                    'one' => [
                        'name' => null,
                    ],
                    'two' => [
                        'name' => null,
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array $result
     * @param array $expected
     *
     * @return void
     */
    protected function assertToArrayResult(array $result, array $expected): void
    {
        foreach ($expected as $key => $value) {
            $this->assertArrayHasKey($key, $result);
            if (is_callable($value)) {
                $value($result[$key]);
            } elseif (is_array($value)) {
                $this->assertToArrayResult($result[$key], $value);
            } else {
                $this->assertSame($value, $result[$key]);
            }
        }
    }

    /**
     * @return string
     */
    protected function getTargetDirectory(): string
    {
        return codecept_data_dir('test_files/Generated/');
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected function getMessenger(): ConsoleLogger
    {
        return new ConsoleLogger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\GeneratorInterface
     */
    protected function getClassGenerator(): GeneratorInterface
    {
        $targetDirectory = static::getTargetDirectory();

        return new ClassGenerator($targetDirectory);
    }

    /**
     * @param array $sourceDirectories
     *
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface
     */
    protected function getDefinitionBuilder(array $sourceDirectories): DefinitionBuilderInterface
    {
        $finder = new TransferDefinitionFinder($sourceDirectories);
        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($finder, $normalizer);
        $definitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition(new TransferConfig())
        );

        return $definitionBuilder;
    }
}
