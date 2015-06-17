<?php
/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Client\Communication;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface ClientControllerListenerInterface
{
    /**
     * @param FilterControllerEvent $event
     * @return callable
     */
    public function onKernelController(FilterControllerEvent $event);
}