<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagRestApi\Processor\EntityTag;

use Spryker\Glue\EntityTagRestApi\Processor\EntityTagResolverInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityTagResponseHeaderFormatter implements EntityTagResponseHeaderFormatterInterface
{
    protected const HEADER_E_TAG = 'Etag';
    /**
     * @var \Spryker\Glue\EntityTagRestApi\Processor\EntityTagResolverInterface
     */
    protected $entityTagResolver;

    /**
     * @param \Spryker\Glue\EntityTagRestApi\Processor\EntityTagResolverInterface $entityTagResolver
     */
    public function __construct(EntityTagResolverInterface $entityTagResolver)
    {
        $this->entityTagResolver = $entityTagResolver;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Response $httpResponse
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function format(Response $httpResponse, RestResponseInterface $restResponse, RestRequestInterface $restRequest): Response
    {
        if (!$this->isMethodApplicable($restRequest->getHttpRequest())) {
            return $httpResponse;
        }
        if (count($restResponse->getResources()) !== 1) {
            return $httpResponse;
        }
        $restResource = $restResponse->getResources()[0];
        $entityTag = $this->entityTagResolver->resolve($restResource);
        if ($entityTag) {
            $httpResponse->headers->set(static::HEADER_E_TAG, $entityTag);
        }

        return $httpResponse;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isMethodApplicable(Request $request): bool
    {
        return $request->isMethod(Request::METHOD_GET) || $request->isMethod(Request::METHOD_POST) || $request->isMethod(Request::METHOD_PATCH);
    }
}
