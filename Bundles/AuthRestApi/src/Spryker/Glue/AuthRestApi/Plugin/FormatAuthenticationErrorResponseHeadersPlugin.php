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

class FormatAuthenticationErrorResponseHeadersPlugin extends AbstractPlugin implements FormatResponseHeadersPluginInterface
{
    /**
     * {@inheritdoc}
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

        if (count($restResponse->getErrors()) === 0) {
            return $httpResponse;
        }

        if (!$this->hasAuthorizationError($restResponse)) {
            return $httpResponse;
        }

        $httpResponse->headers->set('WWW-Authenticate', 'Bearer realm="Access to shop API"');

        return $httpResponse;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return bool
     */
    protected function hasAuthorizationError(RestResponseInterface $restResponse): bool
    {
        foreach ($restResponse->getErrors() as $restErrorMessageTransfer) {
            if ($restErrorMessageTransfer->getStatus() === Response::HTTP_UNAUTHORIZED) {
                return true;
            }
        }

        return false;
    }
}
