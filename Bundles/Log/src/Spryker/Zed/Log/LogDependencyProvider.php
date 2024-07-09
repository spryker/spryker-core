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
    public const PLUGINS_ZED_SECURITY_AUDIT_LOG_PROCESSOR = 'PLUGINS_ZED_SECURITY_AUDIT_LOG_PROCESSOR';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_PORTAL_SECURITY_AUDIT_LOG_PROCESSOR = 'PLUGINS_MERCHANT_PORTAL_SECURITY_AUDIT_LOG_PROCESSOR';

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
    public const PLUGINS_ZED_SECURITY_AUDIT_LOG_HANDLER = 'PLUGINS_ZED_SECURITY_AUDIT_LOG_HANDLER';

    /**
     * @var string
     */
    public const PLUGINS_MERCHANT_PORTAL_SECURITY_AUDIT_LOG_HANDLER = 'PLUGINS_MERCHANT_PORTAL_SECURITY_AUDIT_LOG_HANDLER';

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
        $container = $this->addZedSecurityAuditLogHandlerPlugins($container);
        $container = $this->addMerchantPortalSecurityAuditLogHandlerPlugins($container);
        $container = $this->addProcessors($container);
        $container = $this->addZedSecurityAuditLogProcessorPlugins($container);
        $container = $this->addMerchantPortalSecurityAuditLogProcessorPlugins($container);
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
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addZedSecurityAuditLogHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ZED_SECURITY_AUDIT_LOG_HANDLER, function () {
            return $this->getZedSecurityAuditLogHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    protected function getZedSecurityAuditLogHandlerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPortalSecurityAuditLogHandlerPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_PORTAL_SECURITY_AUDIT_LOG_HANDLER, function () {
            return $this->getMerchantPortalSecurityAuditLogHandlerPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogHandlerPluginInterface>
     */
    protected function getMerchantPortalSecurityAuditLogHandlerPlugins(): array
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

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addZedSecurityAuditLogProcessorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_ZED_SECURITY_AUDIT_LOG_PROCESSOR, function () {
            return $this->getZedSecurityAuditLogProcessorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    protected function getZedSecurityAuditLogProcessorPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addMerchantPortalSecurityAuditLogProcessorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_MERCHANT_PORTAL_SECURITY_AUDIT_LOG_PROCESSOR, function () {
            return $this->getMerchantPortalSecurityAuditLogProcessorPlugins();
        });

        return $container;
    }

    /**
     * @return list<\Spryker\Shared\Log\Dependency\Plugin\LogProcessorPluginInterface>
     */
    protected function getMerchantPortalSecurityAuditLogProcessorPlugins(): array
    {
        return [];
    }
}
