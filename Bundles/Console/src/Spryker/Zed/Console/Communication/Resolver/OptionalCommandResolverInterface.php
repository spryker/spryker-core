<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Console\Communication\Resolver;

use Spryker\Zed\Kernel\Container;
use Symfony\Component\Console\Command\Command;

interface OptionalCommandResolverInterface
{
    /**
     * @return bool
     */
    public function isResolvable(): bool;

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function resolve(Container $container): Command;
}
