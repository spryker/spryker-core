<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList;

use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface;

class ShoppingListDeleter implements ShoppingListDeleterInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

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
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface $shoppingListRestRequestReaderInterface
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilderInterface
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListRestRequestReaderInterface $shoppingListRestRequestReaderInterface,
        ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilderInterface
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListRestRequestReader = $shoppingListRestRequestReaderInterface;
        $this->shoppingListRestResponseBuilder = $shoppingListRestResponseBuilderInterface;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteShoppingList(RestRequestInterface $restRequest): RestResponseInterface
    {
        $uuidShoppingList = $this->shoppingListRestRequestReader->readUuidShoppingList($restRequest);

        $restShoppingListRequestTransfer = $this->shoppingListRestRequestReader->readRestShoppingListRequestTransferByUuid(
            $uuidShoppingList,
            $restRequest
        );

        if (count($restShoppingListRequestTransfer->getErrorCodes()) > 0) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $restShoppingListRequestTransfer->getErrorCodes()
            );
        }

        $shoppingListResponseTransfer = $this->shoppingListsRestApiClient->deleteShoppingList($restShoppingListRequestTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $shoppingListResponseTransfer->getErrors()
            );
        }

        return $this->shoppingListRestResponseBuilder->createRestResponse();
    }
}
