<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\AuthenticatorManager;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface as SymfonyAuthenticatorManagerInterface;

interface AuthenticatorManagerInterface
{
    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     * @param string $firewallName
     * @param array<mixed> $options
     *
     * @return \Symfony\Component\Security\Http\Authentication\AuthenticatorManagerInterface
     */
    public function create(
        ContainerInterface $container,
        string $firewallName,
        array $options
    ): SymfonyAuthenticatorManagerInterface;
}
