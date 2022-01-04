<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Spryker\Zed\Log\Dependency\Facade\LogToLocaleFacadeBridge;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @method \Spryker\Zed\Log\LogConfig getConfig()
 */
class LogDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_QUEUE = 'queue client';

    /**
     * @var string
     */
    public const FILESYSTEM = 'filesystem';

    /**
     * @var string
     */
    public const LOG_PROCESSORS = 'LOG_PROCESSORS';

    /**
     * @var string
     */
    public const LOG_LISTENERS = 'LOG_LISTENERS';

    /**
     * @var string
     */
    public const LOG_HANDLERS = 'LOG_HANDLERS';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideCommunicationLayerDependencies(Container $container)
    {
        $container = $this->addQueueClient($container);
        $container = $this->addLogHandlers($container);
        $container = $this->addProcessors($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container)
    {
        $container = $this->addLogListener($container);
        $container = $this->addFilesystem($container);

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addQueueClient(Container $container)
    {
        $container->set(static::CLIENT_QUEUE, function () use ($container) {
            return $container->getLocator()->queue()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function () use ($container) {
            return new LogToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFilesystem(Container $container)
    {
        $container->set(static::FILESYSTEM, function () {
            return new Filesystem();
        });

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLogListener(Container $container)
    {
        $container->set(static::LOG_LISTENERS, function () {
            return $this->getLogListeners();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Zed\Log\Business\Model\LogListener\LogListenerInterface>
     */
    protected function getLogListeners()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLogHandlers(Container $container)
    {
        $container->set(static::LOG_HANDLERS, function () {
            return $this->getLogHandlers();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    protected function getLogHandlers()
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container*
     */
    protected function addProcessors(Container $container)
    {
        $container->set(static::LOG_PROCESSORS, function () {
            return $this->getLogProcessors();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    protected function getLogProcessors()
    {
        return [];
    }
}
