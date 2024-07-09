<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Log;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Log\Dependency\Client\LogToLocaleClientBridge;

/**
 * @method \Spryker\Yves\Log\LogConfig getConfig()
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
    public const LOG_PROCESSORS = 'LOG_PROCESSORS';

    /**
     * @var string
     */
    public const PLUGINS_YVES_SECURITY_AUDIT_LOG_PROCESSOR = 'PLUGINS_YVES_SECURITY_AUDIT_LOG_PROCESSOR';

    /**
     * @var string
     */
    public const LOG_HANDLERS = 'LOG_HANDLERS';

    /**
     * @var string
     */
    public const PLUGINS_YVES_SECURITY_AUDIT_LOG_HANDLER = 'PLUGINS_YVES_SECURITY_AUDIT_LOG_HANDLER';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addQueueClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addLogHandlers($container);
        $container = $this->addYvesSecurityAuditLogHandlerPlugins($container);
        $container = $this->addProcessors($container);
        $container = $this->addYvesSecurityAuditLogProcessorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQueueClient(Container $container)
    {
        $container->set(static::CLIENT_QUEUE, function () use ($container) {
            return $container->getLocator()->queue()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLogHandlers(Container $container)
    {
        $container->set(static::LOG_HANDLERS, function () {
            return $this->getLogHandlers();
        });

        return $container;
    }

    /**
     * @return array<\Monolog\Handler\HandlerInterface>
     */
    protected function getLogHandlers(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addYvesSecurityAuditLogHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_YVES_SECURITY_AUDIT_LOG_HANDLER, function () {
            return $this->getYvesSecurityAuditLogHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    protected function getYvesSecurityAuditLogHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProcessors(Container $container)
    {
        $container->set(static::LOG_PROCESSORS, function () {
            return $this->getProcessors();
        });

        return $container;
    }

    /**
     * @return array<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    protected function getProcessors(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addYvesSecurityAuditLogProcessorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_YVES_SECURITY_AUDIT_LOG_PROCESSOR, function () {
            return $this->getYvesSecurityAuditLogProcessorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    protected function getYvesSecurityAuditLogProcessorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new LogToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }
}
