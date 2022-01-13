<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

interface RestResourceInterface
{
    /**
     * @var string
     */
    public const RESOURCE_DATA = 'data';

    /**
     * @var string
     */
    public const RESOURCE_TYPE = 'type';

    /**
     * @var string
     */
    public const RESOURCE_ID = 'id';

    /**
     * @var string
     */
    public const RESOURCE_ATTRIBUTES = 'attributes';

    /**
     * @var string
     */
    public const RESOURCE_LINKS = 'links';

    /**
     * @deprecated Use {@link \Spryker\Glue\GlueApplication\Rest\JsonApi\RestLinkInterface::LINK_SELF} instead.
     *
     * @var string
     */
    public const RESOURCE_LINKS_SELF = 'self';

    /**
     * @var string
     */
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
     * @return array<\Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface>
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
