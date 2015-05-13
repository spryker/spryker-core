<?php

namespace Functional\SprykerEngine\Zed\Transfer\Business\Model;

use Codeception\TestCase\Test;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use SprykerEngine\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use SprykerEngine\Zed\Transfer\Business\Model\TransferGenerator;
use SprykerFeature\Zed\Console\Business\Model\ConsoleMessenger;
use Symfony\Component\Console\Logger\ConsoleLogger;
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

    public function testExecuteShouldGenerateExpectedTransfer()
    {
        $messenger = new ConsoleMessenger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        $targetDirectory = __DIR__ . '/Fixtures/Transfer/';
        $classGenerator = new ClassGenerator($targetDirectory);

        $sourceDirectories = [
            __DIR__ . '/Fixtures'
        ];

        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($normalizer, $sourceDirectories);
        $transferDefinitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition()
        );

        $transferGenerator = new TransferGenerator($messenger, $classGenerator, $transferDefinitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($targetDirectory . '/Transfer.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.transfer'),
            file_get_contents($targetDirectory . '/Transfer.php')
        );
    }

    public function testExecuteShouldGenerateExpectedMergedTransfer()
    {
        $messenger = new ConsoleMessenger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        $targetDirectory = __DIR__ . '/Fixtures/Transfer/';
        $classGenerator = new ClassGenerator($targetDirectory);

        $sourceDirectories = [
            __DIR__ . '/Fixtures'
        ];
        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($normalizer, $sourceDirectories);
        $transferDefinitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition()
        );

        $transferGenerator = new TransferGenerator($messenger, $classGenerator, $transferDefinitionBuilder);
        $transferGenerator->execute();

        $this->assertTrue(file_exists($targetDirectory . '/Transfer.php'));
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.merged.transfer'),
            file_get_contents($targetDirectory . '/NameOfTransfer.php')
        );
    }
}
