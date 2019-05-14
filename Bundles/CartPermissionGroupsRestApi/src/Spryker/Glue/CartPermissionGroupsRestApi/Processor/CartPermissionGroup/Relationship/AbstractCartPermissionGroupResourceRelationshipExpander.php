<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup\Relationship;

use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Generated\Shared\Transfer\RestCartPermissionGroupsAttributesTransfer;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

abstract class AbstractCartPermissionGroupResourceRelationshipExpander implements CartPermissionGroupResourceRelationshipExpanderInterface
{
    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface
     */
    protected $cartPermissionGroupResponseBuilder;

    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface
     */
    protected $cartPermissionGroupMapper;

    /**
     * @param \Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupResponseBuilderInterface $cartPermissionGroupResponseBuilder
     * @param \Spryker\Glue\CartPermissionGroupsRestApi\Processor\Mapper\CartPermissionGroupMapperInterface $cartPermissionGroupMapper
     */
    public function __construct(
        CartPermissionGroupResponseBuilderInterface $cartPermissionGroupResponseBuilder,
        CartPermissionGroupMapperInterface $cartPermissionGroupMapper
    ) {
        $this->cartPermissionGroupResponseBuilder = $cartPermissionGroupResponseBuilder;
        $this->cartPermissionGroupMapper = $cartPermissionGroupMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface[] $resources
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return void
     */
    public function addResourceRelationships(array $resources, RestRequestInterface $restRequest): void
    {
        foreach ($resources as $resource) {
            $quotePermissionGroupTransfer = $this->findQuotePermissionGroupTransferInPayload($resource);
            if (!$quotePermissionGroupTransfer) {
                continue;
            }

            $cartPermissionGroupRestResource = $this->createCartPermissionGroupRestResource(
                $quotePermissionGroupTransfer
            );

            $resource->addRelationship($cartPermissionGroupRestResource);
        }
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface $resource
     *
     * @return \Generated\Shared\Transfer\QuotePermissionGroupTransfer|null
     */
    abstract protected function findQuotePermissionGroupTransferInPayload(RestResourceInterface $resource): ?QuotePermissionGroupTransfer;

    /**
     * @param \Generated\Shared\Transfer\QuotePermissionGroupTransfer $quotePermissionGroupTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createCartPermissionGroupRestResource(
        QuotePermissionGroupTransfer $quotePermissionGroupTransfer
    ): RestResourceInterface {
        $restCartPermissionGroupsAttributesTransfer = $this->cartPermissionGroupMapper
            ->mapQuotePermissionGroupTransferToRestCartPermissionGroupsAttributesTransfer(
                $quotePermissionGroupTransfer,
                new RestCartPermissionGroupsAttributesTransfer()
            );

        return $this->cartPermissionGroupResponseBuilder->createCartPermissionGroupsRestResource(
            (string)$quotePermissionGroupTransfer->getIdQuotePermissionGroup(),
            $restCartPermissionGroupsAttributesTransfer,
            $quotePermissionGroupTransfer
        );
    }
}
