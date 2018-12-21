<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ShoppingListsRestApi\Zed;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface;

class ShoppingListsRestApiStub implements ShoppingListsRestApiStubInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface
     */
    protected $zedRequestClient;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToZedRequestClientInterface $zedRequestClient
     */
    public function __construct(ShoppingListsRestApiToZedRequestClientInterface $zedRequestClient)
    {
        $this->zedRequestClient = $zedRequestClient;
    }

    /***
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function addItem(
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): ShoppingListItemTransfer {
        /** @var \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer */
        $shoppingListItemTransfer = $this->zedRequestClient->call(
            '/shopping-lists-rest-api/gateway/add-item',
            $restShoppingListItemRequestTransfer
        );

        return $shoppingListItemTransfer;
    }
}
