<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MessageBroker\Communication\Plugin\Console;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\IncomingMessageTransfer;
use Generated\Shared\Transfer\MessageBrokerTestMessageTransfer;
use Generated\Shared\Transfer\OutgoingMessageTransfer;
use Spryker\Zed\MessageBroker\Business\Exception\AsyncApiFileNotFoundException;
use Spryker\Zed\MessageBroker\Communication\Plugin\Console\MessageBrokerDebugConsole;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester;
use SprykerTest\Zed\MessageBroker\Plugin\IncomingMessageHandlerPlugin;
use SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group MessageBroker
 * @group Communication
 * @group Plugin
 * @group Console
 * @group MessageBrokerDebugConsoleTest
 * Add your own group annotations below this line
 */
class MessageBrokerDebugConsoleTest extends Unit
{
    /**
     * @var string
     */
    public const CHANNEL_NAME = 'test-channel';

    /**
     * @var string
     */
    public const SQS_TRANSPORT_NAME = 'sqs';

    /**
     * @var \SprykerTest\Zed\MessageBroker\MessageBrokerCommunicationTester
     */
    protected MessageBrokerCommunicationTester $tester;

    /**
     * @return void
     */
    public function testPrintsDebugInformationOfConfiguredChannelMessageAndTransport(): void
    {
        // Arrange
        $this->tester->setMessageToChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, static::SQS_TRANSPORT_NAME);

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([]);

        // Assert
        $this->assertSame(MessageBrokerDebugConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('test-channel', $commandTester->getDisplay());
        $this->assertStringContainsString('Generated\Shared\Transfer\MessageBrokerTestMessageTransfer', $commandTester->getDisplay());
        $this->assertStringContainsString('sqs', $commandTester->getDisplay());
        $this->assertStringContainsString('No handler found', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintsDebugInformationOfConfiguredChannelMessageTransportAndHandlerIfConfigured(): void
    {
        // Arrange
        $this->tester->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER, [new SomethingHappenedMessageHandlerPlugin()]);
        $this->tester->setMessageToChannelNameMap(MessageBrokerTestMessageTransfer::class, static::CHANNEL_NAME);
        $this->tester->setChannelToTransportMap(static::CHANNEL_NAME, static::SQS_TRANSPORT_NAME);

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([]);

        //Assert
        $this->assertSame(MessageBrokerDebugConsole::CODE_SUCCESS, $commandTester->getStatusCode());
        $this->assertStringContainsString('test-channel', $commandTester->getDisplay());
        $this->assertStringContainsString('Generated\Shared\Transfer\MessageBrokerTestMessageTransfer', $commandTester->getDisplay());
        $this->assertStringContainsString('sqs', $commandTester->getDisplay());
        $this->assertStringContainsString('SprykerTest\Zed\MessageBroker\Plugin\SomethingHappenedMessageHandlerPlugin', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintDebugThrowsExceptionWhenPathToAsyncApiIsInvalid(): void
    {
        // Expect
        $this->expectException(AsyncApiFileNotFoundException::class);

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([
            '--' . MessageBrokerDebugConsole::OPTION_ASYNC_API_FILE => 'invalid-file-path',
        ]);
    }

    /**
     * @return void
     */
    public function testPrintDebugWithAsyncApiPrintsInformationAboutChannels(): void
    {
        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([
            '--' . MessageBrokerDebugConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('asyncapi/asyncapi.yml'),
        ]);

        // Assert
        $this->assertStringContainsString('channelNameA', $commandTester->getDisplay());
        $this->assertStringContainsString('channelNameB', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintDebugWithAsyncApiPrintsInformationAboutMessagesOthersCanSubscribeToWithMissingTransportAndChannelConfiguration(): void
    {
        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([
            '--' . MessageBrokerDebugConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('asyncapi/asyncapi.yml'),
        ]);

        // Assert
        $this->assertStringContainsString('This application can send the following messages', $commandTester->getDisplay());
        $this->assertStringContainsString('No transport configured', $commandTester->getDisplay());
        $this->assertStringContainsString('Not mapped to a channel', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintDebugWithAsyncApiPrintsInformationAboutMessagesOthersCanSubscribeToWithWrongChannelConfiguration(): void
    {
        // Arrange
        $this->tester->setMessageToChannelNameMap(OutgoingMessageTransfer::class, 'channelNameB');

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([
            '--' . MessageBrokerDebugConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('asyncapi/asyncapi.yml'),
        ]);

        // Assert
        $this->assertStringContainsString('Wrong channel "channelNameB", expected "channelNameA"', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintDebugWithAsyncApiPrintsInformationAboutMessagesOthersCanSubscribeToWithTransportAndChannelConfiguration(): void
    {
        // Arrange
        $this->tester->setMessageToChannelNameMap(OutgoingMessageTransfer::class, 'channelNameA');
        $this->tester->setChannelToTransportMap('channelNameA', 'sqs');

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([
            '--' . MessageBrokerDebugConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('asyncapi/asyncapi.yml'),
        ]);

        // Assert
        $this->assertStringContainsString('sqs', $commandTester->getDisplay());
        $this->assertStringContainsString('channelNameA', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintDebugWithAsyncApiPrintsInformationAboutMessagesOthersCanPublishWithMissingTransportAndChannelConfiguration(): void
    {
        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([
            '--' . MessageBrokerDebugConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('asyncapi/asyncapi.yml'),
        ]);

        // Assert
        $this->assertStringContainsString('channelNameA', $commandTester->getDisplay());
        $this->assertStringContainsString('This application can receive the following messages', $commandTester->getDisplay());
        $this->assertStringContainsString('No transport configured', $commandTester->getDisplay());
        $this->assertStringContainsString('Not mapped to a channel', $commandTester->getDisplay());
    }

    /**
     * @return void
     */
    public function testPrintDebugWithAsyncApiPrintsInformationAboutMessagesOthersCanPublishWithMissingHandler(): void
    {
        // Arrange
        $this->tester->setDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER, [new IncomingMessageHandlerPlugin()]);
        $this->tester->setMessageToChannelNameMap(IncomingMessageTransfer::class, 'channelNameA');
        $this->tester->setChannelToTransportMap('channelNameA', 'sqs');

        // Act
        $commandTester = $this->tester->getDebugConsoleCommandTester();
        $commandTester->execute([
            '--' . MessageBrokerDebugConsole::OPTION_ASYNC_API_FILE => codecept_data_dir('asyncapi/asyncapi.yml'),
        ]);

        // Assert
        $this->assertStringContainsString('channelNameA', $commandTester->getDisplay());
        $this->assertStringContainsString('This application can receive the following messages', $commandTester->getDisplay());
        $this->assertStringContainsString('channelNameA', $commandTester->getDisplay());
        $this->assertStringContainsString('sqs', $commandTester->getDisplay());
        $this->assertStringContainsString('SprykerTest\Zed\MessageBroker\Plugin\IncomingMessageHandlerPlugin', $commandTester->getDisplay());
    }
}
