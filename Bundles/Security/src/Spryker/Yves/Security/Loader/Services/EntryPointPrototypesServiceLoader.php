<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Security\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Security\Http\Authenticator\AuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\HttpBasicAuthenticator;

class EntryPointPrototypesServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ENTRY_POINT_HTTP_PROTO = 'security.entry_point.http._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_ENTRY_POINT_FORM_PROTO = 'security.entry_point.form._proto';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_USER_PROVIDER = 'security.user_provider.';

    /**
     * @var string
     */
    protected const OPTION_LOGIN_PATH = 'login_path';

    /**
     * @var string
     */
    protected const OPTION_USE_FORWARD = 'use_forward';

    /**
     * @var string
     */
    protected const OPTION_REAL_NAME = 'real_name';

    /**
     * @var string
     */
    protected const REAL_NAME_SECURED = 'Secured';

    /**
     * @var string
     */
    protected const URI_LOGIN = '/login';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container = $this->addEntryPointFormPrototype($container);
        $container = $this->addEntryPointHttpPrototype($container);

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addEntryPointFormPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_ENTRY_POINT_FORM_PROTO, $container->protect(function (string $firewallName, array $options) use ($container): callable {
            return function () use ($container, $options, $firewallName): ?AuthenticatorInterface {
                $options[static::OPTION_LOGIN_PATH] = $options[static::OPTION_LOGIN_PATH] ?? static::URI_LOGIN;
                $options[static::OPTION_USE_FORWARD] = $options[static::OPTION_USE_FORWARD] ?? false;

                $formLoginAuthenticatorName = $this->getLoginFormAuthenticatorName($firewallName);

                if (!$container->has($formLoginAuthenticatorName)) {
                    return null;
                }

                return $container->get($formLoginAuthenticatorName);
            };
        }));

        return $container;
    }

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    protected function addEntryPointHttpPrototype(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_ENTRY_POINT_HTTP_PROTO, $container->protect(function (string $firewallName, array $options) use ($container): AuthenticatorInterface {
            return new HttpBasicAuthenticator(
                $options[static::OPTION_REAL_NAME] ?? static::REAL_NAME_SECURED,
                $container->get(static::SERVICE_SECURITY_USER_PROVIDER . $firewallName),
            );
        }));

        return $container;
    }

    /**
     * @param string $firewallName
     *
     * @return string
     */
    protected function getLoginFormAuthenticatorName(string $firewallName): string
    {
        return sprintf('security.%s.login_form.authenticator', $firewallName);
    }
}
