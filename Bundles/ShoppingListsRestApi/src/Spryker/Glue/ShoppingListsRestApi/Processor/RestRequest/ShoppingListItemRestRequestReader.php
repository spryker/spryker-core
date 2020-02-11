<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;

class ShoppingListItemRestRequestReader implements ShoppingListItemRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemRequestTransfer
     */
    public function readShoppingListItemRequestTransferFromRequest(RestRequestInterface $restRequest): ShoppingListItemRequestTransfer
    {
        $uuidShoppingList = $this->readUuidShoppingList($restRequest);
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser());

        return (new ShoppingListItemRequestTransfer())
            ->setShoppingListUuid($uuidShoppingList)
            ->setShoppingListItem($shoppingListItemTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemRequestTransfer
     */
    public function readShoppingListItemRequestTransferByUuid(RestRequestInterface $restRequest): ShoppingListItemRequestTransfer
    {
        $uuidShoppingListItem = $restRequest->getResource()->getId();
        if (!$uuidShoppingListItem) {
            return (new ShoppingListItemRequestTransfer())->addErrorCode(
                SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED
            );
        }

        $shoppingListItemRequestTransfer = $this->readShoppingListItemRequestTransferFromRequest($restRequest);

        if (count($shoppingListItemRequestTransfer->getErrorCodes()) > 0) {
            return $shoppingListItemRequestTransfer;
        }

        $shoppingListItemRequestTransfer->getShoppingListItem()->setUuid($uuidShoppingListItem);

        return $shoppingListItemRequestTransfer;
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
