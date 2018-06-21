<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;

interface RestRequestInterface
{
    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function getResource(): RestResourceInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\FilterInterface[]
     */
    public function getFilters(): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SortInterface[]
     */
    public function getSort(): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface|null
     */
    public function getPage(): ?PageInterface;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SparseFieldInterface[]
     */
    public function getFields(): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\MetadataInterface
     */
    public function getMetadata(): MetadataInterface;

    /**
     * @return array
     */
    public function getRouteContext(): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getParentResources(): array;

    /**
     * @return array
     */
    public function getInclude(): array;

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface
     */
    public function getUser(): ?UserInterface;

    /**
     * @param string $surrogateIdentifier
     * @param string $naturalIdentifier
     * @param array $scopes
     *
     * @return void
     */
    public function setUser(string $surrogateIdentifier, string $naturalIdentifier, array $scopes = []): void;

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getHttpRequest(): Request;

    /**
     * @return bool
     */
    public function getExcludeRelationship(): bool;

    /**
     * @param string $resource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\SparseFieldInterface
     */
    public function getField(string $resource): SparseFieldInterface;

    /**
     * @param string $resource
     *
     * @return bool
     */
    public function hasField(string $resource): bool;

    /**
     * @param string $resource
     *
     * @return bool
     */
    public function hasFilters(string $resource): bool;

    /**
     * @param string $resource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\FilterInterface[]
     */
    public function getFiltersByResource(string $resource): array;
}
