<?php

namespace SprykerFeature\Zed\Sdk\Communication;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

interface SdkControllerListenerInterface
{
    /**
     * @param FilterControllerEvent $event
     * @return callable
     */
    public function onKernelController(FilterControllerEvent $event);
}