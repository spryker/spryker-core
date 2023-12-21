<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\Voter\RoleHierarchyVoter;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class VotersServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_VOTERS = 'security.voters';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_TRUST_RESOLVER = 'security.trust_resolver';

    /**
     * @var \Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface
     */
    protected SecurityConfiguratorInterface $securityConfigurator;

    /**
     * @param \Spryker\Zed\Security\Communication\Configurator\SecurityConfiguratorInterface $securityConfigurator
     */
    public function __construct(SecurityConfiguratorInterface $securityConfigurator)
    {
        $this->securityConfigurator = $securityConfigurator;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_VOTERS, function (ContainerInterface $container): array {
            $securityConfiguration = $this->securityConfigurator->getSecurityConfiguration($container);

            return [
                new RoleHierarchyVoter(
                    new RoleHierarchy($securityConfiguration->getRoleHierarchies()),
                ),
                new AuthenticatedVoter($container->get(static::SERVICE_SECURITY_TRUST_RESOLVER)),
            ];
        });

        return $container;
    }
}
