<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Yves\StorageRouter\Router;

use Symfony\Cmf\Component\Routing\DynamicRouter as SymfonyDynamicRouter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;

class DynamicRouter extends SymfonyDynamicRouter
{
    /**
     * @param \Symfony\Component\Routing\Matcher\RequestMatcherInterface $requestMatcher
     * @param \Symfony\Component\Routing\Generator\UrlGeneratorInterface $urlGenerator
     * @param \Symfony\Cmf\Component\Routing\Enhancer\RouteEnhancerInterface[] $routeEnhancers
     */
    public function __construct(RequestMatcherInterface $requestMatcher, UrlGeneratorInterface $urlGenerator, array $routeEnhancers = [])
    {
        parent::__construct(new RequestContext(), $requestMatcher, $urlGenerator);

        $this->addRouteEnhancers($routeEnhancers);
    }

    /**
     * @param array $routeEnhancers
     *
     * @return void
     */
    protected function addRouteEnhancers(array $routeEnhancers)
    {
        foreach ($routeEnhancers as $routeEnhancer) {
            $this->addRouteEnhancer($routeEnhancer);
        }
    }
}
