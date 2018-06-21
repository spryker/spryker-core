<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

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
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface
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
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\FilterInterface[]
     */
    protected $filters = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface
     */
    protected $user;

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
     * @param null|\Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface $page
     * @param array $routeContext
     * @param array $parentResources
     * @param array $include
     * @param array $fields
     * @param bool $excludeRelationship
     * @param null|\Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface $user
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
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\FilterInterface[]
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
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param string $surrogateIdentifier
     * @param string $naturalIdentifier
     * @param array $scopes
     *
     * @throws \Spryker\Glue\GlueApplication\Rest\Exception\UserAlreadySetException
     *
     * @return void
     */
    public function setUser(string $surrogateIdentifier, string $naturalIdentifier, array $scopes = []): void
    {
        if ($this->user) {
            throw new UserAlreadySetException('Rest request object already have user set.');
        }

        $this->user = new User($surrogateIdentifier, $naturalIdentifier, $scopes);
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
}
