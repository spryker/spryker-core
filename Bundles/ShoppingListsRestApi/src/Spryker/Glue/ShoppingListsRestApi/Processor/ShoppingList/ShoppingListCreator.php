<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList;

use Generated\Shared\Transfer\RestShoppingListAttributesTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper\ShoppingListMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface;

class ShoppingListCreator implements ShoppingListCreatorInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper\ShoppingListMapperInterface
     */
    protected $shoppingListMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface
     */
    protected $shoppingListRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface
     */
    protected $shoppingListRestResponseBuilder;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Mapper\ShoppingListMapperInterface $shoppingListMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface $shoppingListRestRequestReaderInterface
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilderInterface
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
     * @param \Generated\Shared\Transfer\RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function createShoppingList(
        RestRequestInterface $restRequest,
        RestShoppingListAttributesTransfer $restShoppingListAttributesTransfer
    ): RestResponseInterface {
        $restShoppingListRequestTransfer = $this->shoppingListRestRequestReader->readRestShoppingListRequestTransferFromRequest(
            $restRequest
        );

        if (count($restShoppingListRequestTransfer->getErrorCodes()) > 0) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $restShoppingListRequestTransfer->getErrorCodes()
            );
        }

        $restShoppingListRequestTransfer = $this->shoppingListMapper->mapRestShoppingListAttributesTransferToRestShoppingListRequestTransfer(
            $restShoppingListAttributesTransfer,
            $restShoppingListRequestTransfer
        );

        $shoppingListResponseTransfer = $this->shoppingListsRestApiClient->createShoppingList($restShoppingListRequestTransfer);

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
