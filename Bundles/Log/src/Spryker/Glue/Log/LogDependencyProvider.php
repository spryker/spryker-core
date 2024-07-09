<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\Log;

use Spryker\Glue\Kernel\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Container;
use Spryker\Glue\Log\Dependency\Client\LogToLocaleClientBridge;

/**
 * @method \Spryker\Glue\Log\LogConfig getConfig()
 */
class LogDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_QUEUE = 'CLIENT_QUEUE';

    /**
     * @var string
     */
    public const LOG_PROCESSORS = 'LOG_PROCESSORS';

    /**
     * @var string
     */
    public const PLUGINS_GLUE_SECURITY_AUDIT_LOG_PROCESSOR = 'PLUGINS_GLUE_SECURITY_AUDIT_LOG_PROCESSOR';

    /**
     * @var string
     */
    public const PLUGINS_GLUE_BACKEND_SECURITY_AUDIT_LOG_PROCESSOR = 'PLUGINS_GLUE_BACKEND_SECURITY_AUDIT_LOG_PROCESSOR';

    /**
     * @var string
     */
    public const LOG_HANDLERS = 'LOG_HANDLERS';

    /**
     * @var string
     */
    public const PLUGINS_GLUE_SECURITY_AUDIT_LOG_HANDLER = 'PLUGINS_GLUE_SECURITY_AUDIT_LOG_HANDLER';

    /**
     * @var string
     */
    public const PLUGINS_GLUE_BACKEND_SECURITY_AUDIT_LOG_HANDLER = 'PLUGINS_GLUE_BACKEND_SECURITY_AUDIT_LOG_HANDLER';

    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addQueueClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addLogHandlers($container);
        $container = $this->addGlueSecurityAuditLogHandlerPlugins($container);
        $container = $this->addGlueBackendSecurityAuditLogHandlerPlugins($container);
        $container = $this->addProcessors($container);
        $container = $this->addGlueSecurityAuditLogProcessorPlugins($container);
        $container = $this->addGlueBackendSecurityAuditLogProcessorPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addQueueClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUEUE, function () use ($container) {
            return $container->getLocator()->queue()->client();
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new LogToLocaleClientBridge($container->getLocator()->locale()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addLogHandlers(Container $container): Container
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
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueSecurityAuditLogHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GLUE_SECURITY_AUDIT_LOG_HANDLER, function () {
            return $this->getGlueSecurityAuditLogHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    protected function getGlueSecurityAuditLogHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueBackendSecurityAuditLogHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GLUE_BACKEND_SECURITY_AUDIT_LOG_HANDLER, function () {
            return $this->getGlueBackendSecurityAuditLogHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    protected function getGlueBackendSecurityAuditLogHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addProcessors(Container $container): Container
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
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueSecurityAuditLogProcessorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GLUE_SECURITY_AUDIT_LOG_PROCESSOR, function () {
            return $this->getGlueSecurityAuditLogProcessorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    protected function getGlueSecurityAuditLogProcessorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Glue\Kernel\Container $container
     *
     * @return \Spryker\Glue\Kernel\Container
     */
    protected function addGlueBackendSecurityAuditLogProcessorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_GLUE_BACKEND_SECURITY_AUDIT_LOG_PROCESSOR, function () {
            return $this->getGlueBackendSecurityAuditLogProcessorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    protected function getGlueBackendSecurityAuditLogProcessorPlugins(): array
    {
        return [];
    }
}
