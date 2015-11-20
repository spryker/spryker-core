<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\InterfaceDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\InterfaceGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferInterface\TransferInterfaceDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator;
use SprykerFeature\Zed\Console\Business\Model\ConsoleMessenger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group SprykerEngine
 * @group Zed
 * @group Transfer
 * @group Business
 * @group TransferGenerator
 */
class TransferGeneratorTest extends Test
{

    /**
     * @return void
     */
    public function tearDown()
    {
        $targetDirectory = $this->getTargetDirectory();
        $testFiles = [
            'Project/FooBarInterface.php',
            'Vendor/FooBarInterface.php',
        ];

        foreach ($testFiles as $testFile) {
            if (file_exists($targetDirectory . $testFile)) {
                unlink($targetDirectory . $testFile);
            }
        }
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedTransfer()
    {
        $sourceDirectories = [
            __DIR__ . '/Fixtures',
        ];
        $definitionBuilder = $this->getDefinitionBuilder($sourceDirectories);

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($this->getTargetDirectory() . 'CatFaceTransfer.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.transfer'),
            file_get_contents($this->getTargetDirectory() . 'CatFaceTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedMergedTransfer()
    {
        $sourceDirectories = [
            __DIR__ . '/Fixtures/Project/',
            __DIR__ . '/Fixtures/Vendor/',
        ];
        $definitionBuilder = $this->getDefinitionBuilder($sourceDirectories);

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($this->getTargetDirectory() . 'FooBarTransfer.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.merged.transfer'),
            file_get_contents($this->getTargetDirectory() . 'FooBarTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedTransferInterface()
    {
        $sourceDirectories = [
            __DIR__ . '/Fixtures/',
        ];

        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($normalizer, $sourceDirectories);
        $definitionBuilder = new TransferInterfaceDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new InterfaceDefinition()
        );

        $messenger = $this->getMessenger();
        $generator = new InterfaceGenerator($this->getTargetDirectory());

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($this->getTargetDirectory() . 'Test/CatFaceInterface.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.interface'),
            file_get_contents($this->getTargetDirectory() . 'Test/CatFaceInterface.php')
        );
    }

    /**
     * @return string
     */
    protected function getTargetDirectory()
    {
        $targetDirectory = __DIR__ . '/Fixtures/Transfer/';

        return $targetDirectory;
    }

    /**
     * @return ConsoleMessenger
     */
    protected function getMessenger()
    {
        $messenger = new ConsoleMessenger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        return $messenger;
    }

    /**
     * @return ClassGenerator
     */
    protected function getClassGenerator()
    {
        $targetDirectory = $this->getTargetDirectory();
        $generator = new ClassGenerator($targetDirectory);

        return $generator;
    }

    /**
     * @param $sourceDirectories
     *
     * @return TransferDefinitionBuilder
     */
    protected function getDefinitionBuilder($sourceDirectories)
    {
        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($normalizer, $sourceDirectories);
        $definitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition()
        );

        return $definitionBuilder;
    }

}
