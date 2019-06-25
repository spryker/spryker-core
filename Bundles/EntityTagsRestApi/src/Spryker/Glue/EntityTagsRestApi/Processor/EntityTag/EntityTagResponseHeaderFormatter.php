<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\EntityTagsRestApi\Processor\EntityTag;

use Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\RequestConstantsInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class EntityTagResponseHeaderFormatter implements EntityTagResponseHeaderFormatterInterface
{
    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResolverInterface
     */
    protected $entityTagResolver;

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagCheckerInterface
     */
    protected $entityTagChecker;

    /**
     * @var \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface
     */
    protected $entityTagClient;

    /**
     * @param \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagCheckerInterface $entityTagChecker
     * @param \Spryker\Glue\EntityTagsRestApi\Processor\EntityTag\EntityTagResolverInterface $entityTagResolver
     * @param \Spryker\Glue\EntityTagsRestApi\Dependency\Client\EntityTagsRestApiToEntityTagClientInterface $entityTagClient
     */
    public function __construct(
        EntityTagCheckerInterface $entityTagChecker,
        EntityTagResolverInterface $entityTagResolver,
        EntityTagsRestApiToEntityTagClientInterface $entityTagClient
    ) {
        $this->entityTagChecker = $entityTagChecker;
        $this->entityTagResolver = $entityTagResolver;
        $this->entityTagClient = $entityTagClient;
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
        if (count($restResponse->getResources()) !== 1) {
            return $httpResponse;
        }

        $resource = $restResponse->getResources()[0];

        if (!$this->entityTagChecker->isMethodApplicableForAddingEntityTagHeader(
            $restRequest->getHttpRequest()->getMethod(),
            $resource->getType()
        )) {
            return $httpResponse;
        }

        $entityTag = $this->getResourceHash(
            $resource,
            $restRequest->getHttpRequest()->getMethod()
        );

        if ($entityTag) {
            $httpResponse->headers->set(
                RequestConstantsInterface::HEADER_E_TAG,
                $this->formatEntityTag($entityTag)
            );
        }

        return $httpResponse;
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

        return $this->entityTagClient->write(
            $restResource->getType(),
            $restResource->getId(),
            $restResource->getAttributes()->toArray()
        );
    }

    /**
     * @param string $entityTag
     *
     * @return string
     */
    protected function formatEntityTag(string $entityTag): string
    {
        return sprintf('"%s"', $entityTag);
    }
}
