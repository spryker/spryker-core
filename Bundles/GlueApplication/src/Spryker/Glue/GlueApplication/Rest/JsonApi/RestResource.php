<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\GlueApplication\Rest\JsonApi;

use Spryker\Shared\Kernel\Transfer\TransferInterface;

class RestResource implements RestResourceInterface
{
    /**
     * @var string|null
     */
    protected $id;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $links = [];

    /**
     * @var array
     */
    protected $relationships = [];

    /**
     * @var \Spryker\Shared\Kernel\Transfer\TransferInterface
     */
    protected $attributes;

    /**
     * @param string $type
     * @param string|null $id
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $attributes
     */
    public function __construct(string $type, ?string $id = null, ?TransferInterface $attributes = null)
    {
        $this->type = $type;
        $this->id = $id;
        $this->attributes = $attributes;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $restResource
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function addRelationship(RestResourceInterface $restResource): RestResourceInterface
    {
        $this->relationships[$restResource->getType()][] = $restResource;

        return $this;
    }

    /**
     * @param string $type
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function getRelationshipByType(string $type): array
    {
        return $this->relationships[$type];
    }

    /**
     * @return array
     */
    public function getRelationships(): array
    {
        return $this->relationships;
    }

    /**
     * @param string $name
     * @param string $resourceUri
     * @param array $meta
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    public function addLink(string $name, string $resourceUri, array $meta = []): RestResourceInterface
    {
        if (!$meta) {
            $this->links[$name] = $resourceUri;

            return $this;
        }

        $this->links[$name] = [
            'href' => $resourceUri,
            'meta' => $meta,
        ];

        return $this;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasLink(string $name): bool
    {
        return isset($this->links[$name]);
    }

    /**
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return \Spryker\Shared\Kernel\Transfer\TransferInterface|null
     */
    public function getAttributes(): ?TransferInterface
    {
        return $this->attributes;
    }

    /**
     * @return array
     */
    public function getLinks(): array
    {
        return $this->links;
    }

    /**
     * @param bool $includeRelations
     *
     * @return array
     */
    public function toArray($includeRelations = true): array
    {
        $response = [
            RestResourceInterface::RESOURCE_TYPE => $this->type,
            RestResourceInterface::RESOURCE_ID => $this->id,
        ];

        if ($this->attributes) {
            $response[RestResourceInterface::RESOURCE_ATTRIBUTES] = $this->attributes->toArray(true);
        }

        if ($this->links) {
            $response[RestResourceInterface::RESOURCE_LINKS] = $this->links;
        }

        if (!$includeRelations) {
            return $response;
        }

        $relationships = $this->toArrayRelationships();
        if ($relationships) {
            $response[RestResourceInterface::RESOURCE_RELATIONSHIPS] = $relationships;
        }

        return $response;
    }

    /**
     * @return array
     */
    protected function toArrayRelationships(): array
    {
        $relationships = [];
        foreach ($this->relationships as $type => $typeRelationships) {
            if (!isset($relationships[$type])) {
                $relationships[$type][RestResourceInterface::RESOURCE_DATA] = [];
            }

            foreach ($typeRelationships as $relationship) {
                $relationships[$type][RestResourceInterface::RESOURCE_DATA][] = [
                    RestResourceInterface::RESOURCE_TYPE => $type,
                    RestResourceInterface::RESOURCE_ID => $relationship->getId(),
                ];
            }
        }
        return $relationships;
    }
}
