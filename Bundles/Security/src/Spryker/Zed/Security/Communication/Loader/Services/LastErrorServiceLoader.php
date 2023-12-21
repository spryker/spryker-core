<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class LastErrorServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_LAST_ERROR = 'security.last_error';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_LAST_ERROR, $container->protect(function (Request $request): ?string {
            if ($request->attributes->has(Security::AUTHENTICATION_ERROR)) {
                return $request->attributes->get(Security::AUTHENTICATION_ERROR)->getMessage();
            }

            if (!$request->hasSession()) {
                return null;
            }

            /** @var \Symfony\Component\HttpFoundation\Session\SessionInterface $session */
            $session = $request->getSession();

            if ($session->has(Security::AUTHENTICATION_ERROR)) {
                $message = $session->get(Security::AUTHENTICATION_ERROR)->getMessage();
                $session->remove(Security::AUTHENTICATION_ERROR);

                return $message;
            }

            return null;
        }));

        return $container;
    }
}
