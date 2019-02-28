<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Symfony\Component\HttpFoundation\Request;

interface RequestBuilderInterface
{
    /**
     * @param string $resource
     * @param string $field
     * @param string $value
     *
     * @return $this
     */
    public function addFilter(string $resource, string $field, string $value);

    /**
     * @param string $field
     * @param string $direction
     *
     * @return $this
     */
    public function addSort(string $field, string $direction);

    /**
     * @param int $offset
     * @param int $limit
     *
     * @return $this
     */
    public function addPage(int $offset, int $limit);

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface $metadata
     *
     * @return $this
     */
    public function addMetadata(MetadataInterface $metadata);

    /**
     * @param array $routeContext
     *
     * @return $this
     */
    public function addRouteContext(array $routeContext);

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return $this
     */
    public function addParentResource(RestResourceInterface $restResource);

    /**
     * @param string $key
     * @param string $include
     *
     * @return $this
     */
    public function addInclude(string $key, string $include);

    /**
     * @param string $resourceName
     * @param array $fields
     *
     * @return $this
     */
    public function addFields(string $resourceName, array $fields);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $httpRequest
     *
     * @return $this
     */
    public function addHttpRequest(Request $httpRequest);

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getResource(): RestResourceInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface
     */
    public function build(): RestRequestInterface;

    /**
     * @param bool $excludeRelationship
     *
     * @return $this
     */
    public function setExcludeRelationship(bool $excludeRelationship);
}
