<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Kernel\Communication;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface GatewayControllerListenerInterface
{

    /**
     * @param FilterControllerEvent $event
     *
     * @return callable
     */
    public function onKernelController(FilterControllerEvent $event);

}
