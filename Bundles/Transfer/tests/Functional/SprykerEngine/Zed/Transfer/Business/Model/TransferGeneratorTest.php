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

    public function testExecuteShouldGenerateExpectedTransfer()
    {
        $messenger = new ConsoleMessenger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        $targetDirectory = $this->getTargetDirectory();
        $generator = new ClassGenerator($targetDirectory);

        $sourceDirectories = [
            __DIR__ . '/Fixtures',
        ];

        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($normalizer, $sourceDirectories);
        $definitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition()
        );

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($targetDirectory . 'CatFaceTransfer.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.transfer'),
            file_get_contents($targetDirectory . 'CatFaceTransfer.php')
        );
    }

    public function testExecuteShouldGenerateExpectedMergedTransfer()
    {
        $messenger = new ConsoleMessenger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        $targetDirectory = $this->getTargetDirectory();
        $generator = new ClassGenerator($targetDirectory);

        $sourceDirectories = [
            __DIR__ . '/Fixtures/Project/',
            __DIR__ . '/Fixtures/Vendor/',
        ];
        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($normalizer, $sourceDirectories);
        $definitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition()
        );

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($targetDirectory . 'FooBarTransfer.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.merged.transfer'),
            file_get_contents($targetDirectory . 'FooBarTransfer.php')
        );
    }

    public function testExecuteShouldGenerateExpectedTransferInterface()
    {
        $messenger = new ConsoleMessenger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        $targetDirectory = $this->getTargetDirectory();
        $generator = new InterfaceGenerator($targetDirectory);

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

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($targetDirectory . 'Test/CatFaceInterface.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.interface'),
            file_get_contents($targetDirectory . 'Test/CatFaceInterface.php')
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

}
