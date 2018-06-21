<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Plugin\Rest;

use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * @method \Spryker\Glue\GlueApplication\GlueApplicationFactory getFactory()
 */
class GlueRouterPlugin extends AbstractPlugin implements RequestMatcherInterface, UrlGeneratorInterface
{
    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\HttpFoundation\Request $request The request to match
     *
     *
     * @return array An array of parameters
     */
    public function matchRequest(Request $request)
    {
        return $this->getFactory()
            ->createResourceRouter()
            ->matchRequest($request);
    }

    /**
     * Sets the request context.
     *
     * @param \Symfony\Component\Routing\RequestContext $context The context
     *
     * @return void
     */
    public function setContext(RequestContext $context)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @return \Symfony\Component\Routing\RequestContext The context
     */
    public function getContext()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @return string The generated URL
     *
     *                                             it does not match the requirement
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
    }
}
