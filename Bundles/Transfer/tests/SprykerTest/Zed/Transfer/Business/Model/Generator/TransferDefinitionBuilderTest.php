<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Transfer\Business\Model\Generator;

use Codeception\Test\Unit;
use InvalidArgumentException;
use Spryker\Zed\Transfer\Business\Model\Generator\ClassDefinition;
use Spryker\Zed\Transfer\Business\Model\Generator\DefinitionNormalizer;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionBuilder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionFinder;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionLoader;
use Spryker\Zed\Transfer\Business\Model\Generator\TransferDefinitionMerger;
use Spryker\Zed\Transfer\TransferConfig;

/**
 * Auto-generated group annotations
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
    public function testBuildTransferDefinitionShouldReturnArrayWithClassDefinitions()
    {
        $directories = [
            __DIR__ . '/../../../../../../_fixtures/Builder/',
        ];

        $transferDefinitionBuilder = $this->getTransferDefinitionBuilder($directories);

        $result = $transferDefinitionBuilder->getDefinitions();
        $this->assertTrue(is_array($result), print_r($result, true));

        $transferDefinition = $result[0];
        $this->assertInstanceOf(ClassDefinition::class, $transferDefinition);
    }

    /**
     * @return void
     */
    public function testBuildTransferDefinitionWithStrictnessError()
    {
        $sourceDirectories = [
            __DIR__ . '/../../../../../../_fixtures/Shared/Error/Transfer/',
        ];
        $config = $this->getTransferConfigMock();
        $config->expects($this->any())->method('useStrictGeneration')->willReturn(true);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Transfer name `category` does not match expected name `Category` for bundle `Test`');

        $transferDefinitionBuilder = $this->getTransferDefinitionBuilder($sourceDirectories, $config);
        $transferDefinitionBuilder->getDefinitions();
    }

    /**
     * @param array $sourceDirectories
     * @param \Spryker\Zed\Transfer\TransferConfig|null $config
     *
     * @return \Spryker\Zed\Transfer\Business\Model\Generator\DefinitionBuilderInterface
     */
    protected function getTransferDefinitionBuilder($sourceDirectories, ?TransferConfig $config = null)
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
    protected function getTransferConfigMock()
    {
        return $this->getMockBuilder(TransferConfig::class)->setMethods(['useStrictGeneration'])->getMock();
    }
}
