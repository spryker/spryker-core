<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Dependency\Plugin;

use Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface;
use Symfony\Component\HttpFoundation\Request;

interface FormatRequestPluginInterface
{
    /**
     * @api
     *
     * Format http request to internal RestRequest resource
     *
     * @param \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface $requestBuilder
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function format(RequestBuilderInterface $requestBuilder, Request $request): RequestBuilderInterface;
}
