<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Log;

use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;
use Symfony\Component\Filesystem\Filesystem;

class LogDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUEUE = 'queue client';
    public const FILESYSTEM = 'filesystem';

    public const LOG_PROCESSORS = 'LOG_PROCESSORS';
    public const LOG_LISTENERS = 'LOG_LISTENERS';
    public const LOG_HANDLERS = 'LOG_HANDLERS';

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
        $container[self::CLIENT_QUEUE] = function () use ($container) {
            return $container->getLocator()->queue()->client();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addFilesystem(Container $container)
    {
        $container[static::FILESYSTEM] = function () {
            return new Filesystem();
        };

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addLogListener(Container $container)
    {
        $container[static::LOG_LISTENERS] = function () {
            return $this->getLogListeners();
        };

        return $container;
    }

    /**
     * @return \Spryker\Zed\Log\Business\Model\LogListener\LogListenerInterface[]
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
        $container[static::LOG_HANDLERS] = function () {
            return $this->getLogHandlers();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface[]
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
        $container[static::LOG_PROCESSORS] = function () {
            return $this->getLogProcessors();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface[]
     */
    protected function getLogProcessors()
    {
        return [];
    }
}
