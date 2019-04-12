<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouterEnhancer;

use Spryker\Shared\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Symfony\Component\Routing\RequestContext;

abstract class AbstractRouterEnhancerPlugin extends AbstractPlugin implements RouterEnhancerPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @param string $pathinfo
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string
     */
    public function beforeMatch(string $pathinfo, RequestContext $requestContext): string
    {
        return $pathinfo;
    }

    /**
     * {@inheritdoc}
     *
     * @param array $parameters
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return array
     */
    public function afterMatch(array $parameters, RequestContext $requestContext): array
    {
        return $parameters;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $url
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string
     */
    public function afterGenerate(string $url, RequestContext $requestContext): string
    {
        return $url;
    }
}
