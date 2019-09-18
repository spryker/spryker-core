<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use ArrayObject;
use Codeception\Test\Unit;
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
    protected static $transferGenerated;

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testStringProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testStringArrayProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testStringArrayPropertyAdd(GeneratedTransfer $generatedTransfer): void
    {
        // Act
        $generatedTransfer->addTestStringArray('string for array');

        // Assert
        $this->assertSame(['string for array'], $generatedTransfer->getTestStringArray());
    }

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testIntProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testIntArrayProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testIntArrayPropertyAdd(GeneratedTransfer $generatedTransfer): void
    {
        // Act
        $generatedTransfer->addTestIntArray(300);

        // Assert
        $this->assertSame([300], $generatedTransfer->getTestIntArray());
    }

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testBoolProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testBoolArrayProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testBoolArrayPropertyAdd(GeneratedTransfer $generatedTransfer): void
    {
        // Act
        $generatedTransfer->addTestBoolArray(true);

        // Assert
        $this->assertSame([true], $generatedTransfer->getTestBoolArray());
    }

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testArrayProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testTransferProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testTransferCollectionProperty(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testTransferCollectionPropertyAdd(GeneratedTransfer $generatedTransfer): void
    {
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
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testDecimalPropertyOfDecimalType(GeneratedTransfer $generatedTransfer): void
    {
        // Act
        $generatedTransfer->setTestDecimal(1);

        // Assert
        $this->assertInstanceOf(Decimal::class, $generatedTransfer->getTestDecimal());
        $this->assertSame('1', $generatedTransfer->getTestDecimal()->toString());
    }

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testDecimalPropertyToArray(GeneratedTransfer $generatedTransfer): void
    {
        // Act
        $generatedTransfer->setTestDecimal(1);
        $modified = $generatedTransfer->modifiedToArray();

        // Assert
        $this->assertInstanceOf(Decimal::class, $modified['test_decimal']);
        $this->assertSame(['test_decimal' => $generatedTransfer->getTestDecimal()], $modified);
    }

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testDecimalPropertyFromArray(GeneratedTransfer $generatedTransfer): void
    {
        // Act
        $generatedTransfer->fromArray([
            'test_decimal' => 1.01,
        ]);

        // Assert
        $this->assertInstanceOf(Decimal::class, $generatedTransfer->modifiedToArray()['test_decimal']);
    }

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testDecimalPropertyNullable(GeneratedTransfer $generatedTransfer): void
    {
        // Act
        $generatedTransfer->setTestDecimal(null);
        $modified = $generatedTransfer->modifiedToArray();

        // Assert
        $this->assertSame(['test_decimal' => null], $modified);
    }

    /**
     * @dataProvider getTestTransferForTesting
     *
     * @expectedException \Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException
     *
     * @param \Generated\Shared\Transfer\GeneratedTransfer $generatedTransfer
     *
     * @return void
     */
    public function testDecimalPropertyRequire(GeneratedTransfer $generatedTransfer): void
    {
        // Assert
        $generatedTransfer->requireTestDecimal();
    }

    /**
     * @return void
     */
    protected function generateTransfer(): void
    {
        if (static::$transferGenerated) {
            return;
        }

        $definitionBuilder = $this->getDefinitionBuilder([
            codecept_data_dir('GeneratedTest/'),
        ]);

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'GeneratedTransfer.php');
        static::$transferGenerated = true;
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
        $targetDirectory = $this->getTargetDirectory();

        return new ClassGenerator($targetDirectory);
    }

    /**
     * @param array $sourceDirectories
     *
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface
     */
    protected function getDefinitionBuilder($sourceDirectories): DefinitionBuilderInterface
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

    /**
     * @return array
     */
    public function getTestTransferForTesting(): array
    {
        $this->generateTransfer();
        require_once($this->getTargetDirectory() . 'GeneratedTransfer.php');

        return [
            [new GeneratedTransfer()],
        ];
    }
}
