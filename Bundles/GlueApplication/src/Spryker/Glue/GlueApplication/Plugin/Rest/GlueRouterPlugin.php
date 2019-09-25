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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    public function matchRequest(Request $request)
    {
        return $this->getFactory()
            ->createRestResourceRouter()
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
     * @return void
     */
    public function getContext()
    {
    }

    /**
     * {@inheritdoc}
     *
     * @param string $name
     * @param array $parameters
     * @param int $referenceType
     *
     * @return void
     */
    public function generate($name, $parameters = [], $referenceType = self::ABSOLUTE_PATH)
    {
    }
}
