<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList;

use Generated\Shared\Transfer\RestShoppingListsAttributesTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListRestResponseBuilderInterface;

class ShoppingListUpdater implements ShoppingListUpdaterInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface
     */
    protected $shoppingListMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListRestRequestReaderInterface
     */
    protected $shoppingListRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListRestResponseBuilderInterface
     */
    protected $shoppingListRestResponseBuilder;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface $shoppingListMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListRestRequestReaderInterface $shoppingListRestRequestReaderInterface
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilderInterface
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListMapperInterface $shoppingListMapper,
        ShoppingListRestRequestReaderInterface $shoppingListRestRequestReaderInterface,
        ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilderInterface
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListMapper = $shoppingListMapper;
        $this->shoppingListRestRequestReader = $shoppingListRestRequestReaderInterface;
        $this->shoppingListRestResponseBuilder = $shoppingListRestResponseBuilderInterface;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListsAttributesTransfer $restShoppingListsAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function updateShoppingList(
        RestRequestInterface $restRequest,
        RestShoppingListsAttributesTransfer $restShoppingListsAttributesTransfer
    ): RestResponseInterface {
        $shoppingListTransfer = $this->shoppingListRestRequestReader->readShoppingListTransferFromRequest(
            $restRequest,
            $restShoppingListsAttributesTransfer
        );

        $shoppingListResponseTransfer = $this->shoppingListsRestApiClient->updateShoppingList($shoppingListTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $shoppingListResponseTransfer->getErrors()
            );
        }

        return $this->shoppingListRestResponseBuilder->buildShoppingListRestResponse(
            $shoppingListResponseTransfer->getShoppingList()
        );
    }
}
