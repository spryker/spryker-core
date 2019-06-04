<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\EntityTag;

use Spryker\Glue\EntityTagsRestApi\Processor\EntityTagResolverInterface;
use Spryker\Glue\EntityTagsRestApi\Processor\EntityTagWriterInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityTagResponseHeaderFormatter implements EntityTagResponseHeaderFormatterInterface
{
    protected const HEADER_E_TAG = 'ETag';

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagResolverInterface
     */
    protected $entityTagResolver;

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagWriterInterface
     */
    protected $entityTagWriter;

    /**
     * @param \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagResolverInterface $entityTagResolver
     * @param \Spryker\Glue\EntityTagsRestApi\Processor\EntityTagWriterInterface $entityTagWriter
     */
    public function __construct(EntityTagResolverInterface $entityTagResolver, EntityTagWriterInterface $entityTagWriter)
    {
        $this->entityTagResolver = $entityTagResolver;
        $this->entityTagWriter = $entityTagWriter;
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

        $entityTag = $this->getResourceHash(
            $restResponse->getResources()[0],
            $restRequest->getHttpRequest()->getMethod()
        );

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
        return ($request->isMethod(Request::METHOD_GET)
            || $request->isMethod(Request::METHOD_POST)
            || $request->isMethod(Request::METHOD_PATCH)
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param string $httpMethod
     *
     * @return string|null
     */
    protected function getResourceHash(RestResourceInterface $restResource, string $httpMethod): ?string
    {
        if ($httpMethod === Request::METHOD_GET) {
            return $this->entityTagResolver->resolve($restResource);
        }

        return $this->entityTagWriter->write($restResource);
    }
}
