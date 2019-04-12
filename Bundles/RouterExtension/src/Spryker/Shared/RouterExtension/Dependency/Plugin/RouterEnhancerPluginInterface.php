<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Shared\RouterExtension\Dependency\Plugin;

use Symfony\Component\Routing\RequestContext;

interface RouterEnhancerPluginInterface
{
    /**
     * Specification:
     * - Returns a manipulated pathinfo.
     * - Can be used to e.g. remove optional prefix from pathinfo.
     *
     * Symfony's routing component doesn't allow to have optional URL parameter before non-optional ones.
     *
     * We need to allow projects to use optional arguments in the URL e.g. `/{store}/{locale}/foo-bar`.
     * The configured route will only have an URL like `/foo-bar` without the optional arguments but we need to be able
     * to match an incoming route like `/de/de/foo-bar` to the configured one.
     *
     * This method will "normalize" the incoming URL to be matchable.
     *
     * @api
     *
     * @param string $pathinfo
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string
     */
    public function beforeMatch(string $pathinfo, RequestContext $requestContext): string;

    /**
     * Specification:
     * - Adds additional information to the matched $parameters.
     *
     * @api
     *
     * @param array $parameters
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return array
     */
    public function afterMatch(array $parameters, RequestContext $requestContext): array;

    /**
     * Specification:
     * - Returns a manipulated url after it was generated.
     * - Can be used to e.g. add an optional argument to the URL.
     *
     * Symfony's routing component doesn't allow to have optional URL parameter before non-optional ones.
     *
     * We need to allow projects to use optional arguments in the URL e.g. `/{store}/{locale}/foo-bar`.
     * The configured route will only have an URL like `/foo-bar` with a route name e.g. `foo-bar`. After the generator
     * was able to generate an URL by the given route name we need to add the optional arguments to
     * return an URL like `/de/de/foo-bar`.
     *
     * This method will "normalize" the outgoing URL after it was generated.
     *
     * @api
     *
     * @param string $url
     * @param \Symfony\Component\Routing\RequestContext $requestContext
     *
     * @return string
     */
    public function afterGenerate(string $url, RequestContext $requestContext): string;
}
