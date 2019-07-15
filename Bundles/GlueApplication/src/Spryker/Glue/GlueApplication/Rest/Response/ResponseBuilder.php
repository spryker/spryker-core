<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Spryker\Glue\GlueApplication\GlueApplicationConfig;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;

class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface
     */
    protected $responsePagination;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface
     */
    protected $responseRelationship;

    /**
     * @param string $domainName
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponsePaginationInterface $responsePagination
     * @param \Spryker\Glue\GlueApplication\Rest\Response\ResponseRelationshipInterface $responseRelationship
     */
    public function __construct(
        string $domainName,
        ResponsePaginationInterface $responsePagination,
        ResponseRelationshipInterface $responseRelationship
    ) {
        $this->domainName = $domainName;
        $this->responsePagination = $responsePagination;
        $this->responseRelationship = $responseRelationship;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    public function buildResponse(
        RestResponseInterface $restResponse,
        RestRequestInterface $restRequest
    ): array {
        $response = [];

        if (count($restResponse->getResources()) === 0) {
            $response[RestResponseInterface::RESPONSE_DATA] = [];
            $response[RestResponseInterface::RESPONSE_LINKS] = $this->buildCollectionLink($restRequest);

            return $response;
        }

        $mainResourceType = $restResponse->getResources()[0]->getType();

        $this->responseRelationship->loadRelationships(
            $mainResourceType,
            $restResponse->getResources(),
            $restRequest
        );

        $data = $this->resourcesToArray($restResponse->getResources(), $restRequest, $mainResourceType);

        if ($this->isSingleObjectRequest($restRequest, $data)) {
            $response[RestResponseInterface::RESPONSE_DATA] = $data[0];
        } else {
            $response[RestResponseInterface::RESPONSE_DATA] = $data;
            $response[RestResponseInterface::RESPONSE_LINKS] = $this->buildCollectionLink($restRequest);
        }

        $included = $this->responseRelationship->processIncluded($restResponse->getResources(), $restRequest);
        if ($included) {
            $response[RestResponseInterface::RESPONSE_INCLUDED] = $this->resourcesToArray($included, $restRequest, $mainResourceType);
        }

        if ($restRequest->getPage()) {
            $links = $response[RestResponseInterface::RESPONSE_LINKS] ?? [];
            $response[RestResponseInterface::RESPONSE_LINKS] = $links + $this->responsePagination->buildPaginationLinks($restResponse, $restRequest);
        }

        return $response;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $data
     *
     * @return bool
     */
    protected function isSingleObjectRequest(RestRequestInterface $restRequest, array $data): bool
    {
        $id = $restRequest->getResource()->getId();
        $method = $restRequest->getMetadata()->getMethod();

        return count($data) === 1 && (($id && $id !== GlueApplicationConfig::COLLECTION_IDENTIFIER_CURRENT_USER) || $method === Request::METHOD_POST);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param string $currentResourceType
     *
     * @return array
     */
    protected function resourcesToArray(array $resources, RestRequestInterface $restRequest, string $currentResourceType): array
    {
        $data = [];

        foreach ($resources as $resource) {
            if (!$resource->hasLink(RestLinkInterface::LINK_SELF)) {
                $link = $resource->getType();
                if ($resource->getId()) {
                    $link .= '/' . $resource->getId();
                }

                $link .= $this->buildQueryString($resource, $restRequest);

                $resource->addLink(RestLinkInterface::LINK_SELF, $link);
            }
            $includeRelations = $currentResourceType === $resource->getType() || $this->responseRelationship->hasRelationship($resource->getType(), $restRequest);
            $data[] = $this->resourceToArray(
                $resource,
                $includeRelations,
                $restRequest
            );
        }

        return $data;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     * @param bool $includeRelations
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function resourceToArray(
        RestResourceInterface $restResource,
        bool $includeRelations,
        RestRequestInterface $restRequest
    ): array {
        $data = $restResource->toArray($includeRelations);

        if (count($restRequest->getFields()) > 0 && isset($restRequest->getFields()[$restResource->getType()])) {
            $data[RestResourceInterface::RESOURCE_ATTRIBUTES] = array_intersect_key(
                $data[RestResourceInterface::RESOURCE_ATTRIBUTES],
                array_flip($restRequest->getFields()[$restResource->getType()]->getAttributes())
            );
        }

        $data = $this->filterRelationships($restRequest, $data);

        if (isset($data[RestResourceInterface::RESOURCE_LINKS])) {
            $data[RestResourceInterface::RESOURCE_LINKS] = $this->formatLinks(
                $data[RestResourceInterface::RESOURCE_LINKS]
            );
        }

        return $data;
    }

    /**
     * @param array $links
     *
     * @return array
     */
    protected function formatLinks(array $links): array
    {
        $formattedLinks = [];

        foreach ($links as $key => $link) {
            if (is_array($link)) {
                $formattedLinks[$key] = $this->domainName . '/' . $link[$key];

                continue;
            }

            $formattedLinks[$key] = $this->domainName . '/' . $link;
        }

        return $formattedLinks;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function buildCollectionLink(RestRequestInterface $restRequest): array
    {
        $method = $restRequest->getMetadata()->getMethod();
        $idResource = $restRequest->getResource()->getId();

        if ($method === Request::METHOD_GET && ($idResource === null || $this->isCurrentUserCollectionResource($idResource))) {
            $linkParts = [];
            foreach ($restRequest->getParentResources() as $parentResource) {
                $linkParts[] = $parentResource->getType();
                $linkParts[] = $parentResource->getId();
            }
            $linkParts[] = $restRequest->getResource()->getType();
            if ($this->isCurrentUserCollectionResource($idResource)) {
                $linkParts[] = GlueApplicationConfig::COLLECTION_IDENTIFIER_CURRENT_USER;
            }
            $queryString = $this->buildQueryString($restRequest->getResource(), $restRequest);

            return $this->formatLinks([
                RestLinkInterface::LINK_SELF => implode('/', $linkParts) . $queryString,
            ]);
        }

        return [];
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string
     */
    protected function buildQueryString(RestResourceInterface $resource, RestRequestInterface $restRequest): string
    {
        if ($resource->getType() !== $restRequest->getResource()->getType()) {
            return '';
        }

        $queryString = $restRequest->getQueryString();

        if (mb_strlen($queryString)) {
            $queryString = '?' . $queryString;
        }

        return $queryString;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $data
     *
     * @return array
     */
    protected function filterRelationships(RestRequestInterface $restRequest, array $data): array
    {
        if ($restRequest->getExcludeRelationship()) {
            unset($data[RestResourceInterface::RESOURCE_RELATIONSHIPS]);
        }

        if (!array_key_exists(RestResourceInterface::RESOURCE_RELATIONSHIPS, $data)) {
            return $data;
        }

        if ($restRequest->getInclude()) {
            $data[RestResourceInterface::RESOURCE_RELATIONSHIPS] = array_intersect_key(
                $data[RestResourceInterface::RESOURCE_RELATIONSHIPS],
                $restRequest->getInclude()
            );
        }

        if (!$data[RestResourceInterface::RESOURCE_RELATIONSHIPS]) {
            unset($data[RestResourceInterface::RESOURCE_RELATIONSHIPS]);
        }

        return $data;
    }

    /**
     * @param string|null $idResource
     *
     * @return bool
     */
    protected function isCurrentUserCollectionResource(?string $idResource): bool
    {
        return $idResource === GlueApplicationConfig::COLLECTION_IDENTIFIER_CURRENT_USER;
    }
}
