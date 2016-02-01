<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Kernel\Communication;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface GatewayControllerListenerInterface
{

    /**
     * @param \Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     *
     * @return callable
     */
    public function onKernelController(FilterControllerEvent $event);

}
