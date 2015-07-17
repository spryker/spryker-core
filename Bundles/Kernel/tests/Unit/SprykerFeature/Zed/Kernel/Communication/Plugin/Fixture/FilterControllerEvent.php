<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\SprykerFeature\Zed\Kernel\Communication\Plugin\Fixture;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent as SymfonyFilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class FilterControllerEvent extends SymfonyFilterControllerEvent
{

    public function __construct(
        HttpKernelInterface $kernel = null,
        $controller = null,
        Request $request = null,
        $requestType = null
    ) {
    }

}
