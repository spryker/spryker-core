<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use Codeception\TestCase\Test;
use Spryker\Zed\Console\Business\Model\ConsoleMessenger;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\Business\Model\TransferGenerator;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group TransferGeneratorTest
 */
class TransferGeneratorTest extends Test
{

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedTransfer()
    {
        $sourceDirectories = [
            __DIR__ . '/Fixtures/Shared/Test/Transfer/',
        ];
        $definitionBuilder = $this->getDefinitionBuilder($sourceDirectories);
        $this->assertCount(1, $definitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'CatFaceTransfer.php');
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.transfer.php'),
            file_get_contents($this->getTargetDirectory() . 'CatFaceTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedMergedTransfer()
    {
        $sourceDirectories = [
            __DIR__ . '/Fixtures/Project/Test/Transfer/',
            __DIR__ . '/Fixtures/Vendor/Test2/Transfer/',
        ];
        $definitionBuilder = $this->getDefinitionBuilder($sourceDirectories);
        $this->assertCount(1, $definitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'FooBarTransfer.php');
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.merged.transfer.php'),
            file_get_contents($this->getTargetDirectory() . 'FooBarTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedDeprecatedTransfer()
    {
        $sourceDirectories = [
            __DIR__ . '/Fixtures/Shared/Deprecated/Transfer/',
        ];
        $definitionBuilder = $this->getDefinitionBuilder($sourceDirectories);
        $this->assertCount(1, $definitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'DeprecatedFooBarTransfer.php');
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.deprecated.transfer.php'),
            file_get_contents($this->getTargetDirectory() . 'DeprecatedFooBarTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedMergedDeprecatedTransfer()
    {
        $sourceDirectories = [
            __DIR__ . '/Fixtures/Vendor/Deprecated/Transfer/',
            __DIR__ . '/Fixtures/Project/Deprecated/Transfer/',
        ];
        $definitionBuilder = $this->getDefinitionBuilder($sourceDirectories);
        $this->assertCount(1, $definitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'MergedDeprecatedFooBarTransfer.php');
        $this->assertSame(
            file_get_contents(__DIR__ . '/Fixtures/expected.merged.deprecated.transfer.php'),
            file_get_contents($this->getTargetDirectory() . 'MergedDeprecatedFooBarTransfer.php')
        );
    }

    /**
     * @return string
     */
    protected function getTargetDirectory()
    {
        return codecept_output_dir();
    }

    /**
     * @return \Spryker\Zed\Console\Business\Model\ConsoleMessenger
     */
    protected function getMessenger()
    {
        $messenger = new ConsoleMessenger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

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
            new ClassDefinition()
        );

        return $definitionBuilder;
    }

}
