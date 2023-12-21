<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Security\Communication\Loader\Services;

use Spryker\Service\Container\ContainerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Http\HttpUtils;

class UtilsServiceLoader implements ServiceLoaderInterface
{
    /**
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_UTILS = 'security.authentication_utils';

    /**
     * @var string
     */
    protected const SERVICE_SECURITY_HTTP_UTILS = 'security.http_utils';

    /**
     * @uses \Spryker\Zed\Router\Communication\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    protected const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Zed\Http\Communication\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    protected const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Spryker\Service\Container\ContainerInterface
     */
    public function add(ContainerInterface $container): ContainerInterface
    {
        $container->set(static::SERVICE_SECURITY_HTTP_UTILS, function (ContainerInterface $container): HttpUtils {
            $chainRouter = $container->get(static::SERVICE_ROUTER);

            return new HttpUtils($chainRouter, $chainRouter);
        });

        $container->set(static::SERVICE_SECURITY_AUTHENTICATION_UTILS, function (ContainerInterface $container): AuthenticationUtils {
            return new AuthenticationUtils($container->get(static::SERVICE_REQUEST_STACK));
        });

        return $container;
    }
}
