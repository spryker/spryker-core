<?php

/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface RestResourceInterface
{
    public const RESOURCE_DATA = 'data';
    public const RESOURCE_TYPE = 'type';
    public const RESOURCE_ID = 'id';
    public const RESOURCE_ATTRIBUTES = 'attributes';
    public const RESOURCE_LINKS = 'links';

    /**
     * @deprecated use Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface::LINK_SELF instead
     */
    public const RESOURCE_LINKS_SELF = 'self';
    public const RESOURCE_RELATIONSHIPS = 'relationships';

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return $this
     */
    public function addRelationship(RestResourceInterface $restResource);

    /**
     * @return array
     */
    public function getRelationships(): array;

    /**
     * @param string $type
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getRelationshipByType(string $type): array;

    /**
     * @param string $name
     * @param string $resourceUri
     * @param array $meta
     *
     * @return $this
     */
    public function addLink(string $name, string $resourceUri, array $meta = []);

    /**
     * @return array
     */
    public function getLinks(): array;

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasLink(string $name): bool;

    /**
     * @return string|null
     */
    public function getId(): ?string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function getAttributes(): ?AbstractTransfer;

    /**
     * @param bool $includeRelations
     *
     * @return array
     */
    public function toArray($includeRelations = true): array;

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $payload
     *
     * @return $this
     */
    public function setPayload(?AbstractTransfer $payload);

    /**
     * @return \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null
     */
    public function getPayload(): ?AbstractTransfer;
}
