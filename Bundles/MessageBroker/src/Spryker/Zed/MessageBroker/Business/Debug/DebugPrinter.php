<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business\Debug;

use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Exception\AsyncApiFileNotFoundException;
use Spryker\Zed\MessageBroker\MessageBrokerConfig;
use SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface;
use SprykerSdk\AsyncApi\Loader\AsyncApiLoaderInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class DebugPrinter implements DebugPrinterInterface
{
    /**
     * @var \Spryker\Zed\MessageBroker\MessageBrokerConfig
     */
    protected MessageBrokerConfig $config;

    /**
     * @var \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface
     */
    protected ConfigFormatterInterface $configFormatter;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface>
     */
    protected array $receiverPlugins;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected array $senderPlugins;

    /**
     * @var array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface>
     */
    protected array $messageHandlerPlugins;

    /**
     * @var \SprykerSdk\AsyncApi\Loader\AsyncApiLoaderInterface
     */
    protected AsyncApiLoaderInterface $asyncApiLoader;

    /**
     * @param \Spryker\Zed\MessageBroker\MessageBrokerConfig $config
     * @param \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface $configFormatter
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface> $receiverPlugins
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface> $senderPlugins
     * @param array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface> $messageHandlerPlugins
     * @param \SprykerSdk\AsyncApi\Loader\AsyncApiLoaderInterface $asyncApiLoader
     */
    public function __construct(
        MessageBrokerConfig $config,
        ConfigFormatterInterface $configFormatter,
        array $receiverPlugins,
        array $senderPlugins,
        array $messageHandlerPlugins,
        AsyncApiLoaderInterface $asyncApiLoader
    ) {
        $this->config = $config;
        $this->configFormatter = $configFormatter;
        $this->receiverPlugins = $receiverPlugins;
        $this->senderPlugins = $senderPlugins;
        $this->messageHandlerPlugins = $messageHandlerPlugins;
        $this->asyncApiLoader = $asyncApiLoader;
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string|null $pathToAsyncApiFile
     *
     * @return void
     */
    public function printDebug(OutputInterface $output, ?string $pathToAsyncApiFile = null): void
    {
        if ($pathToAsyncApiFile === null) {
            $this->printDebugForConfiguration($output);

            return;
        }

        $this->printDebugForAsyncApi($output, $pathToAsyncApiFile);
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printDebugForConfiguration(OutputInterface $output): void
    {
        $messageToChannelMap = $this->getMessageToChannelMap();
        $channelToTransportMap = $this->getChannelToTransportMap();
        $messagesToHandlerMap = $this->getMessagesToHandlerMap();

        foreach ($messageToChannelMap as $messageClassName => $channelName) {
            $handlersForMessage = $this->getHandlersForMessage($messageClassName, $messagesToHandlerMap);
            $handlersForMessage = count($handlersForMessage) > 0 ? implode(PHP_EOL, $handlersForMessage) : 'No handler found';

            $table = new Table($output);
            $table->setHeaders(['Channel', 'Message', 'Transport', 'Handler']);
            $table->addRow([
                $channelName,
                $messageClassName,
                $channelToTransportMap[$channelName] ?? 'Not configured',
                $handlersForMessage,
            ]);

            $table->render();
        }
    }

    /**
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param string $pathToAsyncApiFile
     *
     * @throws \Spryker\Zed\MessageBroker\Business\Exception\AsyncApiFileNotFoundException
     *
     * @return void
     */
    protected function printDebugForAsyncApi(OutputInterface $output, string $pathToAsyncApiFile): void
    {
        if (!file_exists($pathToAsyncApiFile)) {
            throw new AsyncApiFileNotFoundException(sprintf('Could not find the "%s" AsyncAPI file.', $pathToAsyncApiFile));
        }

        $asyncApi = $this->asyncApiLoader->load($pathToAsyncApiFile);

        foreach ($asyncApi->getChannels() as $channel) {
            $output->writeln('');
            $output->writeln(sprintf('Channel: <fg=yellow>%s</>', $channel->getName()));
            $output->writeln('');

            if (count($this->iterableToArray($channel->getSubscribeMessages())) !== 0) {
                $this->printSubscribeMessageInformation($channel, $output);
            }

            if (count($this->iterableToArray($channel->getPublishMessages())) !== 0) {
                $this->printPublishMessageInformation($channel, $output);
            }
        }
    }

    /**
     * @param \SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface $channel
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printSubscribeMessageInformation(AsyncApiChannelInterface $channel, OutputInterface $output): void
    {
        $output->writeln('<fg=green>This application can send the following messages</>');

        $table = new Table($output);
        $table->setHeaders(['Message', 'Channel', 'Transport']);

        $configuredTransport = $this->getTransportForChannel($channel->getName()) ?? '<fg=red>No transport configured</>';

        foreach ($channel->getSubscribeMessages() as $message) {
            $messageName = sprintf('Generated\Shared\Transfer\%sTransfer', $message->getName());
            $configuredChannel = $this->getChannelNameOutputForMessage($messageName, $channel->getName());

            $table->addRow([
                $messageName,
                $configuredChannel,
                $configuredTransport,
            ]);
        }

        $table->render();
        $output->writeln('');
    }

    /**
     * @param string $messageName
     * @param string $expectedChannelName
     *
     * @return string
     */
    protected function getChannelNameOutputForMessage(string $messageName, string $expectedChannelName): string
    {
        $configuredChannel = $this->getConfiguredChannelForMessage($messageName);

        if ($configuredChannel === null) {
            return '<fg=red>Not mapped to a channel</>';
        }

        if ($configuredChannel !== $expectedChannelName) {
            return sprintf('<fg=red>Wrong channel "%s", expected "%s"</>', $configuredChannel, $expectedChannelName);
        }

        return $configuredChannel;
    }

    /**
     * @param \SprykerSdk\AsyncApi\Channel\AsyncApiChannelInterface $channel
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return void
     */
    protected function printPublishMessageInformation(AsyncApiChannelInterface $channel, OutputInterface $output): void
    {
        $output->writeln('<fg=green>This application can receive the following messages</>');

        $messagesToHandlerMap = $this->getMessagesToHandlerMap();
        $configuredTransport = $this->getTransportForChannel($channel->getName()) ?? '<fg=red>No transport configured</>';

        $table = new Table($output);
        $table->setHeaders(['Message', 'Channel', 'Transport', 'Handler']);

        foreach ($channel->getPublishMessages() as $message) {
            $messageName = sprintf('Generated\Shared\Transfer\%sTransfer', $message->getName());
            $configuredChannel = $this->getChannelNameOutputForMessage($messageName, $channel->getName());

            $handlersForMessage = $this->getHandlersForMessage($messageName, $messagesToHandlerMap);
            $handlersForMessage = count($handlersForMessage) > 0 ? implode(PHP_EOL, $handlersForMessage) : '<fg=red>No handler defined</>';

            $table->addRow([
                $messageName,
                $configuredChannel,
                $configuredTransport,
                $handlersForMessage,
            ]);
        }

        $table->render();
        $output->writeln('');
    }

    /**
     * @param string $channelName
     *
     * @return string|null
     */
    protected function getTransportForChannel(string $channelName): ?string
    {
        $channelToTransportMap = $this->getChannelToTransportMap();

        if (isset($channelToTransportMap[$channelName])) {
            return $channelToTransportMap[$channelName];
        }

        return null;
    }

    /**
     * @param string $messageClassNAme
     *
     * @return string|null
     */
    protected function getConfiguredChannelForMessage(string $messageClassNAme): ?string
    {
        $messageToChannelMap = $this->getMessageToChannelMap();

        if (isset($messageToChannelMap[$messageClassNAme])) {
            return $messageToChannelMap[$messageClassNAme];
        }

        return null;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getMessageToChannelMap(): array
    {
        $messageToChannelMap = $this->config->getMessageToChannelMap();

        if (is_string($messageToChannelMap)) {
            $messageToChannelMap = $this->configFormatter->format($messageToChannelMap);
        }

        return $messageToChannelMap;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getChannelToTransportMap(): array
    {
        $channelToTransportMap = $this->config->getChannelToTransportMap();

        if (is_string($channelToTransportMap)) {
            $channelToTransportMap = $this->configFormatter->format($channelToTransportMap);
        }

        return $channelToTransportMap;
    }

    /**
     * @return array<string, array<string>>
     */
    protected function getMessagesToHandlerMap(): array
    {
        $messagesToHandlerMap = [];

        foreach ($this->messageHandlerPlugins as $messageHandlerPlugin) {
            $messagesToHandlerMap[get_class($messageHandlerPlugin)] = array_keys($this->iterableToArray($messageHandlerPlugin->handles()));
        }

        return $messagesToHandlerMap;
    }

    /**
     * @param string $messageClassName
     * @param array<string, array<string>> $messagesToHandlerMap
     *
     * @return array<string>
     */
    protected function getHandlersForMessage(string $messageClassName, array $messagesToHandlerMap): array
    {
        $handlersForMessage = [];

        foreach ($messagesToHandlerMap as $handlerClassName => $handledMessages) {
            if (in_array($messageClassName, $handledMessages)) {
                $handlersForMessage[] = $handlerClassName;
            }
        }

        return $handlersForMessage;
    }

    /**
     * @param iterable<string, mixed> $iterator
     *
     * @return array<string, mixed>
     */
    protected function iterableToArray(iterable $iterator): array
    {
        return is_array($iterator) ? $iterator : iterator_to_array($iterator);
    }
}
