<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\AuthRestApi\Plugin;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplicationExtension\Dependency\Plugin\FormatResponseHeadersPluginInterface;
use Spryker\Glue\Kernel\AbstractPlugin;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \Spryker\Glue\AuthRestApi\AuthRestApiFactory getFactory()
 */
class FormatAuthenticationErrorResponseHeadersPlugin extends AbstractPlugin implements FormatResponseHeadersPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function format(
        Response $httpResponse,
        RestResponseInterface $restResponse,
        RestRequestInterface $restRequest
    ): Response {
        return $this->getFactory()
            ->createAuthenticationErrorResponseHeadersFormatter()
            ->format($httpResponse, $restResponse);
    }
}
