<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Router\Communication\Plugin\Router\RouterEnhancer;

use Symfony\Component\Routing\RequestContext;
use Zend\Filter\FilterChain;
use Zend\Filter\StringToLower;
use Zend\Filter\Word\CamelCaseToDash;

/**
 * @deprecated Will be removed without replacement. This plugin only exists to support a backwards compatibility to old camelCased URL's which are not supported anymore.
 */
class BackwardsCompatibleUrlRouterEnhancerPlugin extends AbstractRouterEnhancerPlugin
{
    /**
     * @var \Zend\Filter\FilterChain|null
     */
    protected $filter;

    /**
     * {@inheritDoc}
     * - Returns a "normalized" URL for backward compatibility.
     *
     * @example Incoming URL `/fooBar/zipZap` will be converted to `/foo-bar/zip/zap`.
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
        return $this->getFilter()->filter($pathinfo);
    }

    /**
     * @return \Zend\Filter\FilterChain
     */
    protected function getFilter(): FilterChain
    {
        $filter = new FilterChain();
        $filter
            ->attach(new CamelCaseToDash())
            ->attach(new StringToLower());

        return $filter;
    }
}
