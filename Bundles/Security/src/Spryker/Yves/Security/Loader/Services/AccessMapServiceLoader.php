<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface;
use Symfony\Component\HttpFoundation\RequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\Security\Http\AccessMap;
use Symfony\Component\Security\Http\AccessMapInterface;

class AccessMapServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ACCESS_MAP = 'security.access_map';

    /**
     * @var \Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface
     */
    protected SecurityConfiguratorInterface $securityConfigurator;

    /**
     * @param \Spryker\Yves\Security\Configurator\SecurityConfiguratorInterface $securityConfigurator
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
        $container->set(static::SERVICE_SECURITY_ACCESS_MAP, function (ContainerInterface $container): AccessMapInterface {
            $map = new AccessMap();
            $accessRules = $this->securityConfigurator->getSecurityConfiguration($container)->getAccessRules();
            $ruleMappings = $this->mapRules();

            foreach ($accessRules as $rule) {
                $ruleType = gettype($rule[0]);

                if (isset($ruleMappings[$ruleType])) {
                    $map->add($ruleMappings[$ruleType]($rule[0]), (array)$rule[1], $rule[2] ?? null);
                }
            }

            return $map;
        });

        return $container;
    }

    /**
     * @return array<mixed>
     */
    protected function mapRules(): array
    {
        return [
            'string' => function (string $rule): RequestMatcherInterface {
                return new RequestMatcher($rule);
            },
            'array' => function (array $rule): RequestMatcherInterface {
                $rule += [
                    'path' => null,
                    'host' => null,
                    'methods' => null,
                    'ips' => null,
                    'attributes' => [],
                    'schemes' => null,
                ];

                return new RequestMatcher(
                    $rule['path'],
                    $rule['host'],
                    $rule['methods'],
                    $rule['ips'],
                    $rule['attributes'],
                    $rule['schemes'],
                );
            },
        ];
    }
}
