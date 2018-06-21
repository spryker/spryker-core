<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Filter;
use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Page;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequest;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\Sort;
use Spryker\Glue\GlueApplication\Rest\Request\Data\SparseField;
use Symfony\Component\HttpFoundation\Request;

class RequestBuilder implements RequestBuilderInterface
{
    /**
     * @var \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected $resource;

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\FilterInterface[]
     */
    protected $filters = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[]
     */
    protected $sort = [];

    /**
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface
     */
    protected $page;

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
     * @var \Spryker\Glue\GlueApplication\Rest\Request\Data\SparseFieldInterface[]
     */
    protected $fields = [];

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $httpRequest;

    /**
     * @var bool
     */
    protected $excludeRelationship = false;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     */
    public function __construct(RestResourceInterface $resource)
    {
        $this->resource = $resource;
    }

    /**
     * @param string $resource
     * @param string $field
     * @param string $value
     *
     * @return $this
     */
    public function addFilter(string $resource, string $field, string $value): RequestBuilderInterface
    {
        $this->filters[$resource][] = new Filter($resource, $field, $value);

        return $this;
    }

    /**
     * @param string $field
     * @param string $direction
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addSort(string $field, string $direction): RequestBuilderInterface
    {
        $this->sort[] = new Sort($field, $direction);

        return $this;
    }

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addPage(int $offset, int $limit): RequestBuilderInterface
    {
        $this->page = new Page($offset, $limit);

        return $this;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addMetadata(MetadataInterface $metadata): RequestBuilderInterface
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @param array $routeContext
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addRouteContext(array $routeContext): RequestBuilderInterface
    {
        $this->routeContext = $routeContext;

        return $this;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addParentResource(RestResourceInterface $restResource): RequestBuilderInterface
    {
        $this->parentResources[] = $restResource;

        return $this;
    }

    /**
     * @param string $key
     * @param string $include
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addInclude(string $key, string $include): RequestBuilderInterface
    {
        $this->include[$key] = $include;

        return $this;
    }

    /**
     * @param string $resourceName
     * @param array $fields
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addFields(string $resourceName, array $fields): RequestBuilderInterface
    {
        $this->fields[$resourceName] = new SparseField($resourceName, $fields);

        return $this;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function addHttpRequest(Request $httpRequest): RequestBuilderInterface
    {
        $this->httpRequest = $httpRequest;

        return $this;
    }

    /**
     * @param bool $exludeRelationship
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\RequestBuilderInterface
     */
    public function setExcludeRelationship(bool $exludeRelationship): RequestBuilderInterface
    {
        $this->excludeRelationship = $exludeRelationship;

        return $this;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getResource(): RestResourceInterface
    {
        return $this->resource;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function build(): RestRequestInterface
    {
        return new RestRequest(
            $this->resource,
            $this->httpRequest,
            $this->metadata,
            $this->filters,
            $this->sort,
            $this->page,
            $this->routeContext,
            $this->parentResources,
            $this->include,
            $this->fields,
            $this->excludeRelationship
        );
    }
}
