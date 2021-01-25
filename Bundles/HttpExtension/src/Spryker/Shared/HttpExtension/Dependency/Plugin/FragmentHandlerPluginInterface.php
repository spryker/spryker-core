<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\HttpExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Fragment\FragmentHandler;

interface FragmentHandlerPluginInterface
{
    /**
     * Specification:
     * - Extends FragmentHandler with additional components. E.g. with fragment renderers.
     *
     * @api
     *
     * @param \Symfony\Component\HttpKernel\Fragment\FragmentHandler $fragmentHandler
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Symfony\Component\HttpKernel\Fragment\FragmentHandler
     */
    public function extend(FragmentHandler $fragmentHandler, ContainerInterface $container): FragmentHandler;
}
