<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use InvalidArgumentException;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\TransferConfig;
use Symfony\Component\Console\Logger\ConsoleLogger;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Transfer
 * @group Business
 * @group Model
 * @group Generator
 * @group TransferDefinitionBuilderTest
 * Add your own group annotations below this line
 */
class TransferDefinitionBuilderTest extends Unit
{
    /**
     * @return void
     */
    public function testBuildTransferDefinitionShouldReturnArrayWithClassDefinitions(): void
    {
        $directories = [
            codecept_data_dir('Builder/'),
        ];

        $transferDefinitionBuilder = $this->getTransferDefinitionBuilder($directories);

        $result = $transferDefinitionBuilder->getDefinitions($this->getMessengerMock());
        $this->assertTrue(is_array($result), print_r($result, true));

        $transferDefinition = $result[0];
        $this->assertInstanceOf(ClassDefinition::class, $transferDefinition);
    }

    /**
     * @return void
     */
    public function testBuildTransferDefinitionWithStrictnessError(): void
    {
        $sourceDirectories = [
            codecept_data_dir('Shared/Error/Transfer/'),
        ];
        $config = $this->getTransferConfigMock();
        $config->expects($this->any())->method('isTransferNameValidated')->willReturn(true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transfer name `category` does not match expected name `Category` for module `Test`');

        $transferDefinitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $config);
        $transferDefinitionBuilder->getDefinitions($this->getMessengerMock());
    }

    /**
     * @param array<string> $sourceDirectories
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
            new TransferDefinitionMerger(new TransferConfig(), $this->getMessengerMock()),
            new ClassDefinition($config ?: new TransferConfig()),
        );

        return $definitionBuilder;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Transfer\TransferConfig
     */
    protected function getTransferConfigMock(): TransferConfig
    {
        return $this->getMockBuilder(TransferConfig::class)->onlyMethods(['isTransferNameValidated'])->getMock();
    }

    /**
     * @return \Symfony\Component\Console\Logger\ConsoleLogger|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getMessengerMock(): ConsoleLogger
    {
        return $this->getMockBuilder(ConsoleLogger::class)->disableOriginalConstructor()->getMock();
    }
}
