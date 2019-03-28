<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CartPermissionGroupsRestApi\Processor\CartPermissionGroup;

use Generated\Shared\Transfer\QuotePermissionGroupCriteriaFilterTransfer;
use Generated\Shared\Transfer\QuotePermissionGroupTransfer;
use Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface;
use Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupsResponseBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;

class CartPermissionGroupReader implements CartPermissionGroupReaderInterface
{
    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface
     */
    protected $sharedCartClient;

    /**
     * @var \Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupsResponseBuilderInterface
     */
    protected $cartPermissionGroupsResponseBuilder;

    /**
     * @param \Spryker\Glue\CartPermissionGroupsRestApi\Dependency\Client\CartPermissionGroupsRestApiToSharedCartClientInterface $sharedCartClient
     * @param \Spryker\Glue\CartPermissionGroupsRestApi\Processor\ResponseBuilder\CartPermissionGroupsResponseBuilderInterface $cartPermissionGroupsResponseBuilder
     */
    public function __construct(
        CartPermissionGroupsRestApiToSharedCartClientInterface $sharedCartClient,
        CartPermissionGroupsResponseBuilderInterface $cartPermissionGroupsResponseBuilder
    ) {
        $this->sharedCartClient = $sharedCartClient;
        $this->cartPermissionGroupsResponseBuilder = $cartPermissionGroupsResponseBuilder;
    }

    /**
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCartPermissionGroupList(): RestResponseInterface
    {
        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->getQuotePermissionGroupList(
            new QuotePermissionGroupCriteriaFilterTransfer()
        );

        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return $this->cartPermissionGroupsResponseBuilder->createEmptyCartPermissionGroupsResponse();
        }

        return $this->cartPermissionGroupsResponseBuilder->createCartPermissionGroupsCollectionResponse(
            $quotePermissionGroupResponseTransfer->getQuotePermissionGroups()
        );
    }

    /**
     * @param string $idCartPermissionGroup
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function findCartPermissionGroupById(string $idCartPermissionGroup): RestResponseInterface
    {
        $quotePermissionGroupResponseTransfer = $this->sharedCartClient->findQuotePermissionGroupById(
            (new QuotePermissionGroupTransfer())->setIdQuotePermissionGroup((int)$idCartPermissionGroup)
        );

        if (!$quotePermissionGroupResponseTransfer->getIsSuccessful()) {
            return $this->cartPermissionGroupsResponseBuilder->createCartPermissionGroupNotFoundErrorResponse();
        }

        return $this->cartPermissionGroupsResponseBuilder->createCartPermissionGroupsResponse(
            $quotePermissionGroupResponseTransfer->getQuotePermissionGroups()->offsetGet(0)
        );
    }
}
