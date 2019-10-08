<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\Router\Plugin\RouterEnhancer;

use Spryker\Yves\Kernel\AbstractPlugin;
use Spryker\Yves\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface;
use Symfony\Component\Routing\RequestContext;

abstract class AbstractRouterEnhancerPlugin extends AbstractPlugin implements RouterEnhancerPluginInterface
{
    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
     * {@inheritDoc}
     *
     * @param string $url
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     * @param int $referenceType
     *
     * @return string
     */
    public function afterGenerate(string $url, RequestContext $requestContext, int $referenceType): string
    {
        return $url;
    }
}
