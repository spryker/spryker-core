<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\ZedRequest\Communication\Plugin\Fixture;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent as SymfonyFilterControllerEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class FilterControllerEvent extends SymfonyFilterControllerEvent
{
    /**
     * @param \Symfony\Component\HttpKernel\HttpKernelInterface|null $kernel
     * @param callable|null $controller
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param int|null $requestType
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
