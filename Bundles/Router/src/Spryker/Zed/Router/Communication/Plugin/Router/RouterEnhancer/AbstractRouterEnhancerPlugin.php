<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\Router\RouterEnhancer;

use Spryker\Zed\Kernel\Communication\AbstractPlugin;
use Spryker\Zed\RouterExtension\Dependency\Plugin\RouterEnhancerPluginInterface;
use Symfony\Component\Routing\RequestContext;

abstract class AbstractRouterEnhancerPlugin extends AbstractPlugin implements RouterEnhancerPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
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
     * @api
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
     * @api
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
