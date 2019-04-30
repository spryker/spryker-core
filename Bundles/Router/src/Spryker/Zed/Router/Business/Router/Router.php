<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Business\Router;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Routing\Router as SymfonyRouter;

class Router extends SymfonyRouter implements RouterInterface
{
    /**
     * @param \Symfony\Component\Config\Loader\LoaderInterface $loader
     * @param mixed $resource
     * @param array $options
     */
    public function __construct(LoaderInterface $loader, $resource, array $options = [])
    {
        parent::__construct($loader, $resource, $options);
    }
}
