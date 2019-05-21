<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\Exception\UserAlreadySetException;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;

class RestRequest implements RestRequestInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $resource;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[]
     */
    protected $sort;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface|null
     */
    protected $page;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\SparseFieldInterface[]
     */
    protected $fields = [];

    /**
     * @var bool
     */
    protected $excludeRelationship = false;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface
     */
    protected $metadata;

    /**
     * @var array
     */
    protected $routeContext = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    protected $parentResources = [];

    /**
     * @var array
     */
    protected $include = [];

    /**
     * @var array
     */
    protected $filters = [];

    /**
     * @deprecated use $restUser instead.
     *
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface|null
     */
    protected $user;

    /**
     * @var \Generated\Shared\Transfer\RestUserTransfer|null
     */
    protected $restUser;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     * @param array $filters
     * @param array $sort
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface|null $page
     * @param array $routeContext
     * @param array $parentResources
     * @param array $include
     * @param array $fields
     * @param bool $excludeRelationship
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface|null $user
     */
    public function __construct(
        RestResourceInterface $resource,
        Request $httpRequest,
        MetadataInterface $metadata,
        array $filters,
        array $sort,
        ?PageInterface $page,
        array $routeContext,
        array $parentResources,
        array $include,
        array $fields,
        bool $excludeRelationship,
        ?UserInterface $user = null
    ) {

        $this->resource = $resource;
        $this->filters = $filters;
        $this->sort = $sort;
        $this->page = $page;
        $this->fields = $fields;
        $this->metadata = $metadata;
        $this->routeContext = $routeContext;
        $this->parentResources = $parentResources;
        $this->include = $include;
        $this->user = $user;
        $this->httpRequest = $httpRequest;
        $this->excludeRelationship = $excludeRelationship;
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findParentResourceByType(string $type): ?RestResourceInterface
    {
        if (!isset($this->parentResources[$type])) {
            return null;
        }

        return $this->parentResources[$type];
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getResource(): RestResourceInterface
    {
        return $this->resource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\FilterInterface[]
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @param string $resource
     *
     * @return bool
     */
    public function hasFilters(string $resource): bool
    {
        return isset($this->filters[$resource]);
    }

    /**
     * @param string $resource
     *
     * @return array
     */
    public function getFiltersByResource(string $resource): array
    {
        return $this->filters[$resource];
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[]
     */
    public function getSort(): array
    {
        return $this->sort;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface|null
     */
    public function getPage(): ?PageInterface
    {
        return $this->page;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SparseFieldInterface[]
     */
    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @param string $resource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SparseFieldInterface
     */
    public function getField(string $resource): SparseFieldInterface
    {
        return $this->fields[$resource];
    }

    /**
     * @param string $resource
     *
     * @return bool
     */
    public function hasField(string $resource): bool
    {
        return isset($this->fields[$resource]);
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface
     */
    public function getMetadata(): MetadataInterface
    {
        return $this->metadata;
    }

    /**
     * @return array
     */
    public function getRouteContext(): array
    {
        return $this->routeContext;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getParentResources(): array
    {
        return $this->parentResources;
    }

    /**
     * @return array
     */
    public function getInclude(): array
    {
        return $this->include;
    }

    /**
     * @deprecated use getRestUser() instead.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @deprecated use setRestUser() instead.
     *
     * @param string $surrogateIdentifier
     * @param string $naturalIdentifier
     * @param array $scopes
     *
     * @throws \Spryker\Glue\GlueApplication\Rest\Exception\UserAlreadySetException
     *
     * @return void
     */
    public function setUser(
        string $surrogateIdentifier,
        string $naturalIdentifier,
        array $scopes = []
    ): void {
        if ($this->user) {
            throw new UserAlreadySetException('Rest request object already have user set.');
        }

        $this->user = new User($surrogateIdentifier, $naturalIdentifier, $scopes);
    }

    /**
     * @param \Generated\Shared\Transfer\RestUserTransfer|null $restUserTransfer
     *
     * @throws \Spryker\Glue\GlueApplication\Rest\Exception\UserAlreadySetException
     *
     * @return void
     */
    public function setRestUser(?RestUserTransfer $restUserTransfer): void
    {
        if ($this->restUser) {
            throw new UserAlreadySetException('Rest request object already have user set.');
        }

        $this->restUser = $restUserTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function getRestUser(): ?RestUserTransfer
    {
        return $this->restUser;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getHttpRequest(): Request
    {
        return $this->httpRequest;
    }

    /**
     * @return bool
     */
    public function getExcludeRelationship(): bool
    {
        return $this->excludeRelationship;
    }

    /**
     * @return array|null
     */
    public function getAttributesDataFromRequest(): ?array
    {
        if (!isset($this->httpRequest->attributes->get(RestResourceInterface::RESOURCE_DATA)[RestResourceInterface::RESOURCE_ATTRIBUTES])) {
            return null;
        }

        return $this->httpRequest->attributes->get(RestResourceInterface::RESOURCE_DATA)[RestResourceInterface::RESOURCE_ATTRIBUTES];
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface $page
     *
     * @return void
     */
    public function setPage(PageInterface $page): void
    {
        $this->page = $page;
    }

    /**
     * @param string[] $excludeParams
     *
     * @return string
     */
    public function getQueryString(array $excludeParams = []): string
    {
        $queryParams = $this->getHttpRequest()->query->all();
        $queryParams = $this->filterQueryParams($queryParams, $excludeParams);

        return urldecode(http_build_query($queryParams));
    }

    /**
     * @param array $queryParams
     * @param string[] $excludeParams
     *
     * @return array
     */
    protected function filterQueryParams(array $queryParams, array $excludeParams): array
    {
        foreach ($excludeParams as $param) {
            unset($queryParams[$param]);
        }

        return $queryParams;
    }
}
