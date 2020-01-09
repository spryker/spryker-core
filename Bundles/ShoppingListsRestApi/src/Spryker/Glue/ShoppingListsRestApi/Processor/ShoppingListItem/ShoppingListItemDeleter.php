<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListItemRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface;

class ShoppingListItemDeleter implements ShoppingListItemDeleterInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListItemRestRequestReaderInterface
     */
    protected $shoppingListItemRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface
     */
    protected $shoppingListItemRestResponseBuilder;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListItemRestRequestReaderInterface $shoppingListItemRestRequestReader
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListItemRestRequestReaderInterface $shoppingListItemRestRequestReader,
        ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListItemRestRequestReader = $shoppingListItemRestRequestReader;
        $this->shoppingListItemRestResponseBuilder = $shoppingListItemRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function deleteShoppingListItem(
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restShoppingListItemRequestTransfer = $this->shoppingListItemRestRequestReader->readRestShoppingListItemRequestTransferByUuid(
            $restRequest
        );

        if (count($restShoppingListItemRequestTransfer->getErrorCodes()) > 0) {
            return $this->shoppingListItemRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $restShoppingListItemRequestTransfer->getErrorCodes()
            );
        }

        $shoppingListItemResponseTransfer = $this->shoppingListsRestApiClient->deleteShoppingListItem($restShoppingListItemRequestTransfer);

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListItemRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $shoppingListItemResponseTransfer->getErrors()
            );
        }

        return $this->shoppingListItemRestResponseBuilder->createRestResponse();
    }
}
