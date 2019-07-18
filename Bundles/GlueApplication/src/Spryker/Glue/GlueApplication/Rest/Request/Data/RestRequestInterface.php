<?php
/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\Request\Data;

use Generated\Shared\Transfer\RestUserTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Symfony\Component\HttpFoundation\Request;

interface RestRequestInterface
{
    /**
     * @param string $type
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface|null
     */
    public function findParentResourceByType(string $type): ?RestResourceInterface;

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
     * @deprecated use getRestUser() instead.
     *
     * @return \Spryker\Glue\GlueApplication\Rest\Request\Data\UserInterface|null
     */
    public function getUser(): ?UserInterface;

    /**
     * @deprecated use setRestUser() instead.
     *
     * @param string $surrogateIdentifier
     * @param string $naturalIdentifier
     * @param array $scopes
     *
     * @return void
     */
    public function setUser(
        string $surrogateIdentifier,
        string $naturalIdentifier,
        array $scopes = []
    ): void;

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

    /**
     * @return array|null
     */
    public function getAttributesDataFromRequest(): ?array;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\PageInterface $page
     *
     * @return void
     */
    public function setPage(PageInterface $page): void;

    /**
     * @param string[] $excludeParams
     *
     * @return string
     */
    public function getQueryString(array $excludeParams = []): string;

    /**
     * @param \Generated\Shared\Transfer\RestUserTransfer|null $restUserTransfer
     *
     * @throws \Spryker\Glue\GlueApplication\Rest\Exception\UserAlreadySetException
     *
     * @return void
     */
    public function setRestUser(?RestUserTransfer $restUserTransfer): void;

    /**
     * @return \Generated\Shared\Transfer\RestUserTransfer|null
     */
    public function getRestUser(): ?RestUserTransfer;
}
