<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\Relationship;

use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;

class SharedCartExpander implements SharedCartExpanderInterface
{
    public const KEY_UUID = 'KEY_UUID';
    /**
     * @var \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface
     */
    protected $sharedCartReader;

    /**
     * @param \Spryker\Glue\SharedCartsRestApi\Processor\SharedCart\SharedCartReaderInterface $sharedCartReader
     */
    public function __construct(SharedCartReaderInterface $sharedCartReader)
    {
        $this->sharedCartReader = $sharedCartReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[]
     */
    public function addResourceRelationshipsByCartId(array $resources, RestRequestInterface $restRequest): array
    {
        foreach ($resources as $resource) {
            $skuProductConcrete = $this->findUuid($resource->getAttributes());
            if (!$skuProductConcrete) {
                continue;
            }

            $concreteProductsResource = $this->sharedCartReader->getSharedCartsByCartUuid($skuProductConcrete, $restRequest);
            if ($concreteProductsResource) {
                $resource->addRelationship($concreteProductsResource);
            }
        }

        return $resources;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer|null $attributes
     *
     * @return string|null
     */
    protected function findUuid(?AbstractTransfer $attributes): ?string
    {
        if ($attributes
            && $attributes->offsetExists(static::KEY_UUID)
            && $attributes->offsetGet(static::KEY_UUID)
        ) {
            return $attributes->offsetGet(static::KEY_UUID);
        }

        return null;
    }
}
