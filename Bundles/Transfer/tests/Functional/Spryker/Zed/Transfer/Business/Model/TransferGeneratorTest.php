<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Transfer\Business\Model;

use Codeception\TestCase\Test;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator;
use Spryker\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\Business\Model\TransferGenerator;
use Spryker\Zed\Console\Business\Model\ConsoleMessenger;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @group Spryker
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
            __DIR__ . '/Fixtures/',
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
     * @return string
     */
    protected function getTargetDirectory()
    {
        $targetDirectory = __DIR__ . '/Fixtures/Transfer/';

        return $targetDirectory;
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
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\Transfer\ClassGenerator
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
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\Transfer\TransferDefinitionBuilder
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
