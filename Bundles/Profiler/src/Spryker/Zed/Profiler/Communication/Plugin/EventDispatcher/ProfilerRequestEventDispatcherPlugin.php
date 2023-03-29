<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Profiler\Communication\Plugin\EventDispatcher;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\EventDispatcher\EventDispatcherInterface;
use Spryker\Shared\EventDispatcherExtension\Dependency\Plugin\EventDispatcherPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Symfony\Component\HttpKernel\Event\KernelEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * @method \Spryker\Zed\Profiler\Communication\ProfilerCommunicationFactory getFactory()
 * @method \Spryker\Zed\Profiler\ProfilerConfig getConfig()
 */
class ProfilerRequestEventDispatcherPlugin extends AbstractPlugin implements EventDispatcherPluginInterface
{
    /**
     * @var int
     */
    protected const LISTENER_PRIORITY = 50;

    /**
     * {@inheritDoc}
     * - Adds listener for `KernelEvents::REQUEST`.
     * - Adds listener for `KernelEvents::EXCEPTION`.
     * - Adds listener for `KernelEvents::RESPONSE`.
     *
     * @api
     *
     * @param \Spryker\Shared\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Shared\EventDispatcher\EventDispatcherInterface
     */
    public function extend(EventDispatcherInterface $eventDispatcher, ContainerInterface $container): EventDispatcherInterface
    {
        $eventDispatcher->addListener(KernelEvents::REQUEST, [$this, 'onRequestStart'], static::LISTENER_PRIORITY);
        $eventDispatcher->addListener(KernelEvents::EXCEPTION, [$this, 'onRequestFinish'], static::LISTENER_PRIORITY);
        $eventDispatcher->addListener(KernelEvents::RESPONSE, [$this, 'onRequestFinish'], static::LISTENER_PRIORITY);

        return $eventDispatcher;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\KernelEvent $event
     *
     * @return void
     */
    public function onRequestStart(KernelEvent $event): void
    {
        if (!$this->isProfilerEnabled()) {
            return;
        }

        xhprof_enable(XHPROF_FLAGS_NO_BUILTINS);
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Event\KernelEvent $event
     *
     * @return void
     */
    public function onRequestFinish(KernelEvent $event): void
    {
        if (!$this->isProfilerEnabled()) {
            return;
        }

        $xhprofData = xhprof_disable();

        if (!is_array($xhprofData)) {
            return;
        }

        $profilerOutputData = $this->getFactory()->createProfilerCallTraceVisualizer()->visualizeProfilerCallTrace($xhprofData);
        $this->getFactory()->createProfilerDataStorage()->logProfilerData($profilerOutputData);
    }

    /**
     * @return bool
     */
    protected function isProfilerEnabled(): bool
    {
        return extension_loaded('xhprof') && $this->getConfig()->isProfilerEnabled();
    }
}
