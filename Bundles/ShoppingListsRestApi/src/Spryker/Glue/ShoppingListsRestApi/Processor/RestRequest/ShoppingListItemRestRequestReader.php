<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;

class ShoppingListItemRestRequestReader implements ShoppingListItemRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferFromRequest(RestRequestInterface $restRequest): RestShoppingListItemRequestTransfer
    {
        $uuidShoppingList = $this->readUuidShoppingList($restRequest);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());

        return (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid($uuidShoppingList)
            ->setShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferByUuid(RestRequestInterface $restRequest): RestShoppingListItemRequestTransfer
    {
        $uuidShoppingListItem = $restRequest->getResource()->getId();
        if (!$uuidShoppingListItem) {
            return (new RestShoppingListItemRequestTransfer())->addErrorCode(
                SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED
            );
        }

        $restShoppingListItemRequestTransfer = $this->readRestShoppingListItemRequestTransferFromRequest($restRequest);

        if (count($restShoppingListItemRequestTransfer->getErrorCodes()) > 0) {
            return $restShoppingListItemRequestTransfer;
        }

        $restShoppingListItemRequestTransfer->getShoppingListItem()->setUuid($uuidShoppingListItem);

        return $restShoppingListItemRequestTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function readUuidShoppingList(RestRequestInterface $restRequest): ?string
    {
        $shoppingListResource = $restRequest->findParentResourceByType(ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS);

        if ($shoppingListResource !== null) {
            return $shoppingListResource->getId();
        }

        return null;
    }
}
