<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\TwigExtension\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Twig\Environment;

interface TwigPluginInterface
{
    /**
     * Specification:
     * - Allows to extend Twig with additional functionality (e.g. functions, global variables, etc.).
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment;
}
