<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Security\Dependency\Plugin;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Security\Configuration\SecurityBuilderInterface;

interface SecurityPluginInterface
{
    /**
     * Specification:
     * - Extends the Security service.
     *
     * @api
     *
     * @param \Spryker\Yves\Security\Configuration\SecurityBuilderInterface $securityBuilder
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Yves\Security\Configuration\SecurityBuilderInterface
     */
    public function extend(SecurityBuilderInterface $securityBuilder, ContainerInterface $container): SecurityBuilderInterface;
}
