<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MessageBroker\Business;

use Monolog\Handler\HandlerInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;
use Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface;
use Spryker\Zed\MessageBroker\Business\Config\JsonToArrayConfigFormatter;
use Spryker\Zed\MessageBroker\Business\Debug\DebugPrinter;
use Spryker\Zed\MessageBroker\Business\Debug\DebugPrinterInterface;
use Spryker\Zed\MessageBroker\Business\MessageAttributeProvider\MessageAttributeProvider;
use Spryker\Zed\MessageBroker\Business\MessageAttributeProvider\MessageAttributeProviderInterface;
use Spryker\Zed\MessageBroker\Business\MessageHandler\MessageHandlerLocator;
use Spryker\Zed\MessageBroker\Business\MessageSender\MessageSenderLocator;
use Spryker\Zed\MessageBroker\Business\MessageValidator\MessageValidatorStack;
use Spryker\Zed\MessageBroker\Business\MessageValidator\MessageValidatorStackInterface;
use Spryker\Zed\MessageBroker\Business\Publisher\MessagePublisher;
use Spryker\Zed\MessageBroker\Business\Publisher\MessagePublisherInterface;
use Spryker\Zed\MessageBroker\Business\Worker\Worker;
use Spryker\Zed\MessageBroker\Business\Worker\WorkerInterface;
use Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface;
use Spryker\Zed\MessageBroker\MessageBrokerDependencyProvider;
use SprykerSdk\AsyncApi\Loader\AsyncApiLoader;
use SprykerSdk\AsyncApi\Loader\AsyncApiLoaderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\HandlersLocatorInterface;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\SendMessageMiddleware;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

/**
 * @method \Spryker\Zed\MessageBroker\MessageBrokerConfig getConfig()
 */
class MessageBrokerBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @var string
     */
    protected const LOGGER_NAME = 'messageBrokerLogger';

    /**
     * @return \Spryker\Zed\MessageBroker\Business\Publisher\MessagePublisherInterface
     */
    public function createMessagePublisher(): MessagePublisherInterface
    {
        return new MessagePublisher(
            $this->createMessageDecorator(),
            $this->createMessageBus(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\MessageAttributeProvider\MessageAttributeProviderInterface
     */
    public function createMessageDecorator(): MessageAttributeProviderInterface
    {
        return new MessageAttributeProvider(
            $this->getMessageDecoratorPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageAttributeProviderPluginInterface>
     */
    protected function getMessageDecoratorPlugins(): array
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_ATTRIBUTE_PROVIDER);
    }

    /**
     * @return \Symfony\Component\Messenger\MessageBusInterface
     */
    public function createMessageBus(): MessageBusInterface
    {
        return new MessageBus(
            $this->getMiddlewares(),
        );
    }

    /**
     * @return array<\Symfony\Component\Messenger\Middleware\MiddlewareInterface>
     */
    public function getMiddlewares(): array
    {
        return array_merge($this->getMiddlewarePlugins(), [
            $this->createSendMessageMiddleware(),
            $this->createHandleMessageMiddleware(),
        ]);
    }

    /**
     * @return \Symfony\Component\Messenger\Middleware\SendMessageMiddleware
     */
    public function createSendMessageMiddleware(): MiddlewareInterface
    {
        return new SendMessageMiddleware(
            $this->createMessageSenderLocator(),
        );
    }

    /**
     * @return \Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface
     */
    public function createMessageSenderLocator(): SendersLocatorInterface
    {
        return new MessageSenderLocator(
            $this->getConfig(),
            $this->createConfigFormatter(),
            $this->getMessageSenderPlugins(),
        );
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\Config\ConfigFormatterInterface
     */
    public function createConfigFormatter(): ConfigFormatterInterface
    {
        return new JsonToArrayConfigFormatter($this->getUtilEncodingService());
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageSenderPluginInterface>
     */
    protected function getMessageSenderPlugins(): array
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_SENDER);
    }

    /**
     * @return \Symfony\Component\Messenger\Middleware\MiddlewareInterface
     */
    public function createHandleMessageMiddleware(): MiddlewareInterface
    {
        return new HandleMessageMiddleware(
            $this->createMessageHandlerLocator(),
        );
    }

    /**
     * @return \Symfony\Component\Messenger\Handler\HandlersLocatorInterface
     */
    public function createMessageHandlerLocator(): HandlersLocatorInterface
    {
        return new MessageHandlerLocator(
            $this->getMessageHandlerPlugins(),
        );
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageHandlerPluginInterface>
     */
    protected function getMessageHandlerPlugins(): array
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_HANDLER);
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\Worker\WorkerInterface
     */
    public function createWorker(): WorkerInterface
    {
        return new Worker(
            $this->getMessageReceiverPlugins(),
            $this->createMessageBus(),
            $this->getEventDispatcher(),
            $this->getConfig(),
            $this->createLogger(),
        );
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageReceiverPluginInterface>
     */
    public function getMessageReceiverPlugins(): array
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::PLUGINS_MESSAGE_RECEIVER);
    }

    /**
     * @return \Symfony\Component\EventDispatcher\EventDispatcherInterface
     */
    public function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::EVENT_DISPATCHER);
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\Debug\DebugPrinterInterface
     */
    public function createDebugPrinter(): DebugPrinterInterface
    {
        return new DebugPrinter(
            $this->getConfig(),
            $this->createConfigFormatter(),
            $this->getMessageReceiverPlugins(),
            $this->getMessageSenderPlugins(),
            $this->getMessageHandlerPlugins(),
            $this->createAsyncApiLoader(),
        );
    }

    /**
     * @return \SprykerSdk\AsyncApi\Loader\AsyncApiLoaderInterface
     */
    public function createAsyncApiLoader(): AsyncApiLoaderInterface
    {
        return new AsyncApiLoader();
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Business\MessageValidator\MessageValidatorStackInterface
     */
    public function createMessageValidatorStack(): MessageValidatorStackInterface
    {
        return new MessageValidatorStack($this->getInternalValidatorPlugins(), $this->getExternalValidatorPlugins());
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface>
     */
    public function getInternalValidatorPlugins(): array
    {
        return [];
    }

    /**
     * @return array<\Spryker\Zed\MessageBrokerExtension\Dependency\Plugin\MessageValidatorPluginInterface>
     */
    public function getExternalValidatorPlugins(): array
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::PLUGINS_EXTERNAL_VALIDATOR);
    }

    /**
     * @return array<\Symfony\Component\Messenger\Middleware\MiddlewareInterface>
     */
    protected function getMiddlewarePlugins(): array
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::PLUGINS_MIDDLEWARE);
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface
     */
    protected function getUtilEncodingService(): MessageBrokerToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \Psr\Log\LoggerInterface|null
     */
    public function createLogger(): ?LoggerInterface
    {
        if (!$this->getConfig()->isLoggingEnabled()) {
            return null;
        }

        $logger = new Logger(static::LOGGER_NAME);
        $logger->pushHandler(
            $this->createStreamHandler(),
        );

        return $logger;
    }

    /**
     * @return \Monolog\Handler\HandlerInterface
     */
    public function createStreamHandler(): HandlerInterface
    {
        return new StreamHandler($this->getConfig()->getLogFilePath());
    }

    /**
     * @return \Spryker\Zed\MessageBroker\Dependency\Service\MessageBrokerToUtilEncodingServiceInterface
     */
    protected function getExternalValidator(): MessageBrokerToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(MessageBrokerDependencyProvider::SERVICE_UTIL_ENCODING);
    }
}
