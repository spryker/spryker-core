<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerFeatureTest\Zed\SelfServicePortal\Communication\Plugin\StateMachine;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\StateMachineItemTransfer;
use SprykerFeature\Zed\SelfServicePortal\Communication\Plugin\StateMachine\SspInquiryStateMachineHandlerPlugin;
use SprykerFeature\Zed\SelfServicePortal\Persistence\SelfServicePortalRepositoryInterface;
use SprykerFeature\Zed\SelfServicePortal\SelfServicePortalConfig;
use SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester;

/**
 * @group SprykerFeatureTest
 * @group Zed
 * @group SelfServicePortal
 * @group Communication
 * @group Plugin
 * @group StateMachine
 * @group SspInquiryStateMachineHandlerPluginTest
 */
class SspInquiryStateMachineHandlerPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_PROCESS_NAME = 'TestProcess';

    /**
     * @var string
     */
    protected const TEST_INITIAL_STATE = 'TestInitialState';

    /**
     * @var \SprykerFeatureTest\Zed\SelfServicePortal\SelfServicePortalCommunicationTester
     */
    protected SelfServicePortalCommunicationTester $tester;

    public function testGetCommandPlugins(): void
    {
        // Arrange
        $plugin = new SspInquiryStateMachineHandlerPlugin();

        // Act
        $commandPlugins = $plugin->getCommandPlugins();

        // Assert
        $this->assertIsArray($commandPlugins);
    }

    public function testGetConditionPlugins(): void
    {
        // Arrange
        $plugin = new SspInquiryStateMachineHandlerPlugin();

        // Act
        $conditionPlugins = $plugin->getConditionPlugins();

        // Assert
        $this->assertIsArray($conditionPlugins);
    }

    public function testGetStateMachineName(): void
    {
        // Arrange
        $configMock = $this->createMock(SelfServicePortalConfig::class);
        $configMock->method('getInquiryStateMachineName')->willReturn('ssp-inquiry');

        $plugin = $this->createPluginWithMockedConfig($configMock);

        // Act
        $actualStateMachineName = $plugin->getStateMachineName();

        // Assert
        $this->assertSame('ssp-inquiry', $actualStateMachineName);
    }

    public function testGetActiveProcesses(): void
    {
        // Arrange
        $processMap = [
            'type1' => 'Process1',
            'type2' => 'Process2',
            'type3' => 'Process2',
        ];

        $configMock = $this->createMock(SelfServicePortalConfig::class);
        $configMock->method('getSspInquiryStateMachineProcessInquiryTypeMap')->willReturn($processMap);

        $plugin = $this->createPluginWithMockedConfig($configMock);

        // Act
        $activeProcesses = $plugin->getActiveProcesses();

        // Assert
        $this->assertCount(2, $activeProcesses);
        $this->assertContains('Process1', $activeProcesses);
        $this->assertContains('Process2', $activeProcesses);
    }

    public function testGetInitialStateForProcess(): void
    {
        // Arrange
        $initialStateMap = [
            static::TEST_PROCESS_NAME => static::TEST_INITIAL_STATE,
        ];

        $configMock = $this->createMock(SelfServicePortalConfig::class);
        $configMock->method('getInquiryInitialStateMachineMap')->willReturn($initialStateMap);

        $plugin = $this->createPluginWithMockedConfig($configMock);

        // Act
        $initialState = $plugin->getInitialStateForProcess(static::TEST_PROCESS_NAME);

        // Assert
        $this->assertSame(static::TEST_INITIAL_STATE, $initialState);
    }

    public function testGetStateMachineItemsByStateIds(): void
    {
        // Arrange
        $stateIds = [1, 2, 3];

        $expectedItems = [
            (new StateMachineItemTransfer())->setIdentifier(101)->setIdItemState(1),
            (new StateMachineItemTransfer())->setIdentifier(102)->setIdItemState(2),
        ];

        $repositoryMock = $this->createMock(SelfServicePortalRepositoryInterface::class);
        $repositoryMock->method('getStateMachineItemsByStateIds')
            ->with($stateIds)
            ->willReturn($expectedItems);

        $plugin = $this->createPluginWithMockedRepository($repositoryMock);

        // Act
        $actualItems = $plugin->getStateMachineItemsByStateIds($stateIds);

        // Assert
        $this->assertCount(count($expectedItems), $actualItems);
        $this->assertEquals($expectedItems, $actualItems);
    }

    protected function createPluginWithMockedConfig(SelfServicePortalConfig $configMock): SspInquiryStateMachineHandlerPlugin
    {
        $plugin = $this->getMockBuilder(SspInquiryStateMachineHandlerPlugin::class)
            ->onlyMethods(['getConfig'])
            ->disableOriginalConstructor()
            ->getMock();

        $plugin->method('getConfig')->willReturn($configMock);

        return $plugin;
    }

    protected function createPluginWithMockedRepository(SelfServicePortalRepositoryInterface $repositoryMock): SspInquiryStateMachineHandlerPlugin
    {
        $plugin = $this->getMockBuilder(SspInquiryStateMachineHandlerPlugin::class)
            ->onlyMethods(['getRepository'])
            ->disableOriginalConstructor()
            ->getMock();

        $plugin->method('getRepository')->willReturn($repositoryMock);

        return $plugin;
    }
}
