<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\Application\Business\Routing;

use Pimple;
use Psr\Log\LoggerInterface;
use Silex\RedirectableUrlMatcher;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouterInterface;

/**
 * The default router, which matches/generates all the routes
 * add by the methods in Application
 */
class SilexRouter implements RouterInterface
{
    /**
     * @var \Pimple
     */
    protected $app;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    protected $logger;

    /**
     * @var \Symfony\Component\Routing\RequestContext|null
     */
    protected $context;

    /**
     * @param \Pimple $app
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(Pimple $app, ?LoggerInterface $logger = null)
    {
        $this->app = $app;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     *
     * @param \Symfony\Component\Routing\RequestContext $context
     *
     * @return void
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * @inheritDoc
     */
    public function getContext()
    {
        return ($this->context) ?: $this->app['request_context'];
    }

    /**
     * @inheritDoc
     */
    public function getRouteCollection()
    {
        return $this->app['routes'];
    }

    /**
     * @inheritDoc
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
        $generator = new UrlGenerator($this->getRouteCollection(), $this->getContext(), $this->logger);

        return $generator->generate($name, $parameters, $referenceType);
    }

    /**
     * Tries to match a URL path with a set of routes.
     *
     * If the matcher can not find information, it must throw one of the exceptions documented
     * below.
     *
     * @param string $pathinfo The path info to be parsed (raw format, i.e. not urldecoded)
     *
     * @return array An array of parameters
     */
    public function match($pathinfo)
    {
        $matcher = new RedirectableUrlMatcher($this->getRouteCollection(), $this->getContext());

        return $matcher->match($pathinfo);
    }
}
