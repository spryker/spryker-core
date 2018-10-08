<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

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

        $data = $this->resourcesToArray($restResponse->getResources(), $restRequest);

        if ($this->isSingleObjectRequest($restRequest, $data)) {
            $response[RestResponseInterface::RESPONSE_DATA] = $data[0];
        } else {
            $response[RestResponseInterface::RESPONSE_DATA] = $data;
            $response[RestResponseInterface::RESPONSE_LINKS] = $this->buildCollectionLink($restRequest);
        }

        $included = $this->responseRelationship->processIncluded($restResponse->getResources(), $restRequest);
        if ($included) {
            $response[RestResponseInterface::RESPONSE_INCLUDED] = $this->resourcesToArray($included, $restRequest);
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

        return count($data) === 1 && ($id || $method === Request::METHOD_POST);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function resourcesToArray(array $resources, RestRequestInterface $restRequest): array
    {
        $data = [];
        foreach ($resources as $resource) {
            if (!$resource->hasLink(RestResourceInterface::RESOURCE_LINKS_SELF)) {
                $link = $resource->getType();
                if ($resource->getId()) {
                    $link .= '/' . $resource->getId();
                }
                $resource->addLink(RestResourceInterface::RESOURCE_LINKS_SELF, $link);
            }
            $data[] = $this->resourceToArray(
                $resource,
                $this->responseRelationship->hasRelationship($resource->getType(), $restRequest),
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
                $link['href'] = $this->domainName . '/' . $link['href'];
                $formattedLinks[$key] = $link;
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

        if ($method === Request::METHOD_GET && $idResource === null) {
            $linkParts = [];
            foreach ($restRequest->getParentResources() as $parentResource) {
                $linkParts[] = $parentResource->getType();
                $linkParts[] = $parentResource->getId();
            }
            $linkParts[] = $restRequest->getResource()->getType();
            return $this->formatLinks([
                RestResourceInterface::RESOURCE_LINKS_SELF => implode('/', $linkParts),
            ]);
        }
        return [];
    }
}
