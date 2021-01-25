<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder;

use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\RestShoppingListsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListRestResponseBuilder extends RestResponseBuilder implements ShoppingListRestResponseBuilderInterface
{
    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\RestResponseBuilderInterface
     */
    protected $restResponseBuilder;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface
     */
    protected $shoppingListsResourceMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface
     */
    protected $shoppingListItemRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface
     */
    protected $glossaryStorageClient;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceBuilderInterface $restResourceBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface $shoppingListsResourceMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
     * @param \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
     */
    public function __construct(
        RestResourceBuilderInterface $restResourceBuilder,
        ShoppingListMapperInterface $shoppingListsResourceMapper,
        ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder,
        ShoppingListsRestApiToGlossaryStorageClientInterface $glossaryStorageClient
    ) {
        parent::__construct($restResourceBuilder, $glossaryStorageClient);

        $this->shoppingListsResourceMapper = $shoppingListsResourceMapper;
        $this->shoppingListItemRestResponseBuilder = $shoppingListItemRestResponseBuilder;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildShoppingListRestResponse(
        ShoppingListTransfer $shoppingListTransfer
    ): RestResponseInterface {
        return $this->createRestResponse()->addResource($this->createShoppingListRestResource($shoppingListTransfer));
    }

    /**
     * @param \Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function buildShoppingListCollectionRestResponse(
        RestShoppingListCollectionResponseTransfer $restShoppingListCollectionResponseTransfer
    ): RestResponseInterface {
        $restResponse = $this->createRestResponse();

        foreach ($restShoppingListCollectionResponseTransfer->getShoppingLists() as $shoppingListTransfer) {
            $restResponse->addResource($this->createShoppingListRestResource($shoppingListTransfer));
        }

        return $restResponse;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListTransfer $shoppingListTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResourceInterface
     */
    protected function createShoppingListRestResource(
        ShoppingListTransfer $shoppingListTransfer
    ): RestResourceInterface {
        $restShoppingListsAttributesTransfer = $this->shoppingListsResourceMapper->mapShoppingListTransferToRestShoppingListsAttributesTransfer(
            $shoppingListTransfer,
            new RestShoppingListsAttributesTransfer()
        );

        return $this->restResourceBuilder->createRestResource(
            ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS,
            $shoppingListTransfer->getUuid(),
            $restShoppingListsAttributesTransfer
        )->setPayload($shoppingListTransfer);
    }
}
