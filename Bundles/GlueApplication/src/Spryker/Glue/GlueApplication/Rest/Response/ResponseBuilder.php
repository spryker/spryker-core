<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Response;

use Generated\Shared\Transfer\RestPageOffsetsTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface;

class ResponseBuilder implements ResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface
     */
    protected $resourceRelationshipProviderLoader;

    /**
     * @var string
     */
    protected $domainName;

    /**
     * @var array
     */
    protected $includedResources = [];

    /**
     * @var array
     */
    protected $alreadyLoadedResources = [];

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader
     * @param string $domainName
     */
    public function __construct(
        ResourceRelationshipLoaderInterface $resourceRelationshipProviderLoader,
        string $domainName
    ) {
        $this->resourceRelationshipProviderLoader = $resourceRelationshipProviderLoader;
        $this->domainName = $domainName;
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

        if (count($restResponse->getResources()) === 0) {
            return [];
        }

        $mainResourceType = $restResponse->getResources()[0]->getType();

        $this->loadRelationships(
            $mainResourceType,
            $restResponse->getResources(),
            $restRequest
        );

        $data = $this->resourcesToArray($restResponse->getResources(), $restRequest);
        if (count($data) === 1) {
            $response[RestResponseInterface::RESPONSE_DATA] = $data[0];
        } else {
            $response[RestResponseInterface::RESPONSE_DATA] = $data;
        }

        $included = $this->processIncluded($restResponse->getResources(), $restRequest);
        if ($included) {
            $response[RestResponseInterface::RESPONSE_INCLUDED] = $this->resourcesToArray($included, $restRequest);
        }

        if ($restRequest->getPage()) {
            $response[RestResponseInterface::RESPONSE_LINKS] = $this->buildPaginationLinks($restResponse, $restRequest);
        }

        return $response;
    }

    /**
     * @param string $resourceName
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    protected function loadRelationships(
        string $resourceName,
        array $resources,
        RestRequestInterface $restRequest
    ): void {

        if ($restRequest->getExcludeRelationship() === true) {
            return;
        }

        if (isset($this->alreadyLoadedResources[$resourceName])) {
            return;
        }

        $relationshipPlugins = $this->resourceRelationshipProviderLoader->load($resourceName);
        foreach ($relationshipPlugins as $relationshipPlugin) {
            if (!$this->hasRelationship($relationshipPlugin->getRelationshipResourceType(), $restRequest)) {
                continue;
            }

            $relationshipPlugin->addResourceRelationships($resources, $restRequest);
        }

        $this->alreadyLoadedResources[$resourceName] = true;

        foreach ($resources as $resource) {
            foreach ($resource->getRelationships() as $type => $resourceRelationships) {
                if (!$this->hasRelationship($type, $restRequest)) {
                    continue;
                }
                $this->loadRelationships($type, $resourceRelationships, $restRequest);
            }
        }
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
            if (!$resource->hasLink('self')) {
                $resource->addLink('self', $resource->getType() . '/' . $resource->getId());
            }
            $data[] = $this->resourceToArray(
                $resource,
                $this->hasRelationship($resource->getType(), $restRequest),
                $restRequest
            );
        }
        return $data;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function processIncluded(array $resources, RestRequestInterface $restRequest): array
    {
        $included = [];
        foreach ($resources as $resource) {
            $included[] = $this->processRelationships($resource->getRelationships(), $restRequest);
        }

        return array_merge(...$included);
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
     * @param array $relations
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param array $included
     *
     * @return array
     */
    protected function processRelationships(
        array $relations,
        RestRequestInterface $restRequest,
        array $included = []
    ): array {

        foreach ($relations as $type => $typeRelationships) {
            if (!$this->hasRelationship($type, $restRequest)) {
                continue;
            }
            foreach ($typeRelationships as $resource) {
                $haveRelations = count($resource->getRelationships()) > 0;
                if ($haveRelations) {
                    $included = $this->processRelationships($resource->getRelationships(), $restRequest, $included);
                }

                $resourceIdentifier = $type . ':' . $resource->getId();
                if (isset($this->includedResources[$resourceIdentifier])) {
                    continue;
                }

                $this->includedResources[$resourceIdentifier] = true;
                $included[] = $resource;
            }
        }

        return $included;
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
            if (\is_array($link)) {
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
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     *
     * @return \Generated\Shared\Transfer\RestPageOffsetsTransfer|null
     */
    protected function calculatePaginationOffset(
        RestRequestInterface $restRequest,
        RestResponseInterface $restResponse
    ): ?RestPageOffsetsTransfer {

        if (!$restRequest->getPage() || !$restResponse->getTotals()) {
            return null;
        }

        $limit = $restResponse->getLimit() ? $restResponse->getLimit() : $restRequest->getPage()->getLimit();
        $offset = $restRequest->getPage()->getOffset();

        $totalPages = floor($restResponse->getTotals() / $limit);

        $prevOffset = $offset - $limit;
        if ($prevOffset < 0) {
            $prevOffset = 0;
        }

        $nextOffset = $offset + $limit;
        if ($nextOffset > $totalPages) {
            $nextOffset = ($totalPages / $limit) * $limit;
        }

        $restPageOffsetsTransfer = (new RestPageOffsetsTransfer())
            ->setLimit($limit)
            ->setLastOffset($restResponse->getTotals() - $limit)
            ->setNextOffset($nextOffset)
            ->setPrevOffset($prevOffset);

        return $restPageOffsetsTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface $restResponse
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return array
     */
    protected function buildPaginationLinks(
        RestResponseInterface $restResponse,
        RestRequestInterface $restRequest
    ): array {

        $pageOffsetsTransfer = $this->calculatePaginationOffset($restRequest, $restResponse);

        if (!$pageOffsetsTransfer) {
            return [];
        }

        $domain = sprintf($this->domainName . '/%s?page[offset]=', $restRequest->getResource()->getType());

        $limit = '';
        if ($pageOffsetsTransfer->getLimit()) {
            $limit = '&page[limit]=' . $pageOffsetsTransfer->getLimit();
        }

        $ofsetLinks = [
            'next' => $domain . $pageOffsetsTransfer->getNextOffset() . $limit,
            'prev' => $domain . $pageOffsetsTransfer->getPrevOffset() . $limit,
            'last' => $domain . $pageOffsetsTransfer->getLastOffset() . $limit,
            'first' => $domain . 0 . $limit,
        ];

        return array_merge(
            $ofsetLinks,
            $restResponse->getLinks()
        );
    }

    /**
     * @param string $type
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return bool
     */
    protected function hasRelationship(string $type, RestRequestInterface $restRequest): bool
    {
        if ($restRequest->getResource()->getType() === $type) {
            return true;
        }

        $includes = $restRequest->getInclude();
        return (count($includes) > 0 && isset($includes[$type])) || (count($includes) === 0 && !$restRequest->getExcludeRelationship());
    }
}
