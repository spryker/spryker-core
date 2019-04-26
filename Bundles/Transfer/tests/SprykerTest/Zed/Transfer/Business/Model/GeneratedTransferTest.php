<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GeneratedTransfer;
use Spryker\Shared\Kernel\Transfer\Exception\RequiredTransferPropertyException;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
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
     * @return void
     */
    public function testStringProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testStringArrayProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testStringArrayPropertyAdd()
    {
        $generatedTransfer = $this->getTestTransferForTesting();
        $generatedTransfer->addTestStringArray('string for array');
        $this->assertSame(['string for array'], $generatedTransfer->getTestStringArray());
    }

    /**
     * @return void
     */
    public function testIntProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testIntArrayProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testIntArrayPropertyAdd()
    {
        $generatedTransfer = $this->getTestTransferForTesting();
        $generatedTransfer->addTestIntArray(300);
        $this->assertSame([300], $generatedTransfer->getTestIntArray());
    }

    /**
     * @return void
     */
    public function testBoolProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testBoolArrayProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testBoolArrayPropertyAdd()
    {
        $generatedTransfer = $this->getTestTransferForTesting();
        $generatedTransfer->addTestBoolArray(true);
        $this->assertSame([true], $generatedTransfer->getTestBoolArray());
    }

    /**
     * @return void
     */
    public function testArrayProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

        $generatedTransfer->setTestArray([]);
        $this->assertSame([], $generatedTransfer->getTestArray());
        $this->assertIsArray($generatedTransfer->getTestArray());

        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_array' => []], $modified);

        $generatedTransfer->setTestArray(null);
        $modified = $generatedTransfer->modifiedToArray();
        $this->assertSame(['test_array' => []], $modified);
    }

    /**
     * @return void
     */
    public function testTransferProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testTransferCollectionProperty()
    {
        $generatedTransfer = $this->getTestTransferForTesting();

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
    public function testTransferCollectionPropertyAdd()
    {
        $generatedTransfer = $this->getTestTransferForTesting();
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
    public function testTransferCollectionSetAndAddModified()
    {
        $generatedTransferSet = $this->getTestTransferForTesting();
        $transferCollection = new ArrayObject([
            new GeneratedTransfer(),
            new GeneratedTransfer(),
        ]);
        $generatedTransferSet->setTestTransfers($transferCollection);

        $generatedTransferAdd = $this->getTestTransferForTesting();
        $generatedTransferAdd
            ->addTransfer(new GeneratedTransfer())
            ->addTransfer(new GeneratedTransfer());

        $this->assertEquals($generatedTransferSet, $generatedTransferAdd);
    }

    /**
     * @return void
     */
    public function testFromArrayWithUnderScoreNames()
    {
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
        $generatedTransfer->fromArray($generatedTransferData);

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
     * @return string
     */
    protected function getTargetDirectory()
    {
        return __DIR__ . '/../../_data/Generated/';
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected function getMessenger()
    {
        $messenger = new ConsoleLogger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        return $messenger;
    }

    /**
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\GeneratorInterface
     */
    protected function getClassGenerator()
    {
        $targetDirectory = $this->getTargetDirectory();
        $generator = new ClassGenerator($targetDirectory);

        return $generator;
    }

    /**
     * @param array $sourceDirectories
     *
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface
     */
    protected function getDefinitionBuilder($sourceDirectories)
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
     * @return \Generated\Shared\Transfer\GeneratedTransfer
     */
    protected function getTestTransferForTesting()
    {
        $sourceDirectories = [
            codecept_data_dir('GeneratedTest/'),
        ];
        $definitionBuilder = $this->getDefinitionBuilder($sourceDirectories);

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'GeneratedTransfer.php');

        require_once($this->getTargetDirectory() . 'GeneratedTransfer.php');

        return new GeneratedTransfer();
    }
}
