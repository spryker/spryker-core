<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model;

use Codeception\Test\Unit;
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
 * @group TransferGeneratorTest
 * Add your own group annotations below this line
 * @property \SprykerTest\Zed\Transfer\TransferBusinessTester $tester
 */
class TransferGeneratorTest extends Unit
{
    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedTransfer(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Test/Transfer/'),
        ];
        $transferDefinitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $this->getTransferConfigMock());
        $this->assertCount(1, $transferDefinitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $transferDefinitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'CatFaceTransfer.php');
        $this->assertSame(
            file_get_contents(codecept_data_dir('test_files/expected.transfer.php')),
            file_get_contents($this->getTargetDirectory() . 'CatFaceTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteWithStrictnessTransfer(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Test/Transfer/'),
        ];
        $config = $this->getTransferConfigMock();
        $config->expects($this->any())->method('isTransferNameValidated')->willReturn(true);

        $definitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $config);
        $this->assertCount(1, $definitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'CatFaceTransfer.php');
        $this->assertSame(
            file_get_contents(codecept_data_dir('test_files/expected.transfer.php')),
            file_get_contents($this->getTargetDirectory() . 'CatFaceTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedMergedTransfer(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Project/Test/Transfer/'),
            codecept_data_dir('Vendor/Test2/Transfer/'),
        ];
        $definitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $this->getTransferConfigMock());
        $this->assertCount(2, $definitionBuilder->getDefinitions(), 'Expected to get 2 class definitions.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'FooBarTransfer.php');
        $this->assertSame(
            file_get_contents(codecept_data_dir('test_files/expected.merged.transfer.php')),
            file_get_contents($this->getTargetDirectory() . 'FooBarTransfer.php')
        );

        $this->assertFileExists($this->getTargetDirectory() . 'AnEmptyOneTransfer.php');
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedDeprecatedTransfer(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Deprecated/Transfer/'),
        ];
        $definitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $this->getTransferConfigMock());
        $this->assertCount(1, $definitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'DeprecatedFooBarTransfer.php');
        $this->assertSame(
            file_get_contents(codecept_data_dir('test_files/expected.deprecated.transfer.php')),
            file_get_contents($this->getTargetDirectory() . 'DeprecatedFooBarTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testExecuteShouldGenerateExpectedMergedDeprecatedTransfer(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Vendor/Deprecated/Transfer/'),
            codecept_data_dir('Project/Deprecated/Transfer/'),
        ];
        $definitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $this->getTransferConfigMock());
        $this->assertCount(1, $definitionBuilder->getDefinitions(), 'Expected to get 1 class definition.');

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $definitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'MergedDeprecatedFooBarTransfer.php');

        $this->assertSame(
            file_get_contents(codecept_data_dir('test_files/expected.merged.deprecated.transfer.php')),
            file_get_contents($this->getTargetDirectory() . 'MergedDeprecatedFooBarTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testTypeShimShouldBeAppliedToDocblock(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Test/Transfer/'),
        ];
        $configMock = $this->getTransferConfigMock();
        $configMock->method('getTypeShims')->willReturn([
            'CatFace' => [
                'name' => [
                    'string' => 'int',
                ],
            ],
        ]);
        $transferDefinitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $configMock);

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $transferDefinitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'CatFaceTransfer.php');
        $this->assertSame(
            file_get_contents(codecept_data_dir('test_files/expected.shimmed.transfer.php')),
            file_get_contents($this->getTargetDirectory() . 'CatFaceTransfer.php')
        );
    }

    /**
     * @return void
     */
    public function testTypeAssertionShouldBeInjectedIfConfigured(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Test/Transfer/'),
        ];
        $configMock = $this->createMock(TransferConfig::class);
        $configMock->method('isSetterTypeAssertionEnabled')->willReturn(true);
        $transferDefinitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $configMock);

        $messenger = $this->getMessenger();
        $generator = $this->getClassGenerator();

        $transferGenerator = new TransferGenerator($messenger, $generator, $transferDefinitionBuilder);
        $transferGenerator->execute();

        $this->assertFileExists($this->getTargetDirectory() . 'CatFaceTransfer.php');
        $this->assertSame(
            file_get_contents(codecept_data_dir('test_files/expected.typecheck.transfer.php')),
            file_get_contents($this->getTargetDirectory() . 'CatFaceTransfer.php')
        );
    }

    /**
     * @return string
     */
    protected function getTargetDirectory(): string
    {
        return codecept_output_dir();
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger
     */
    protected function getMessenger(): ConsoleLogger
    {
        $messenger = new ConsoleLogger(new ConsoleOutput(OutputInterface::VERBOSITY_QUIET));

        return $messenger;
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
     * @param \Spryker\Zed\Transfer\TransferConfig|null $config
     *
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface
     */
    protected function getTransferDefinitionBuilder(array $sourceDirectories, ?TransferConfig $config = null): DefinitionBuilderInterface
    {
        $finder = new TransferDefinitionFinder($sourceDirectories);
        $normalizer = new DefinitionNormalizer();
        $loader = new TransferDefinitionLoader($finder, $normalizer);
        $definitionBuilder = new TransferDefinitionBuilder(
            $loader,
            new TransferDefinitionMerger(),
            new ClassDefinition($config ?: new TransferConfig())
        );

        return $definitionBuilder;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Transfer\TransferConfig
     */
    protected function getTransferConfigMock(): TransferConfig
    {
        $configMock = $this->createMock(TransferConfig::class);
        $configMock->method('isSetterTypeAssertionEnabled')->willReturn(false);

        return $configMock;
    }
}
