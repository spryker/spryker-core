<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Unit\Spryker\Zed\Kernel\Communication\Plugin\Fixture;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent as SymfonyFilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class FilterControllerEvent extends SymfonyFilterControllerEvent
{

    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param null $controller
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param null $requestType
     */
    public function __construct(
        HttpKernelInterface $kernel = null,
        $controller = null,
        Request $request = null,
        $requestType = null
    ) {
        unset($kernel, $controller, $request, $requestType);
    }

}
