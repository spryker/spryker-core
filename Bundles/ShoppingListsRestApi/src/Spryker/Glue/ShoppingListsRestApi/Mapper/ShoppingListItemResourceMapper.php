<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Mapper;

use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListItemResourceMapper implements ShoppingListItemResourceMapperInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapShoppingListItemTransferFromRestRequest(
        RestRequestInterface $restRequest,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): ShoppingListItemTransfer {
        $shoppingListItemTransfer = (new ShoppingListItemTransfer())->fromArray(
            $restShoppingListItemAttributesTransfer->toArray(),
            true
        );

        if (!$restRequest->getUser()) {
            return $shoppingListItemTransfer;
        }

        $shoppingListItemTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer
     */
    public function mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): RestShoppingListItemAttributesTransfer {
        return (new RestShoppingListItemAttributesTransfer())->fromArray(
            $shoppingListItemTransfer->toArray(),
            true
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function mapRestShoppingListItemRequestTransferFromRestRequest(
        RestRequestInterface $restRequest,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): RestShoppingListItemRequestTransfer {
        $restShoppingListItemRequestTransfer = (new RestShoppingListItemRequestTransfer())->setShoppingListItem($shoppingListItemTransfer);

        if (!$restRequest->getUser()) {
            return $restShoppingListItemRequestTransfer;
        }

        $restShoppingListItemRequestTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        $shoppingListResource = $restRequest->findParentResourceByType(ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS);

        if (!$shoppingListResource) {
            return $restShoppingListItemRequestTransfer;
        }

        $restShoppingListItemRequestTransfer->setShoppingListUuid($shoppingListResource->getId())
            ->setCompanyUserUuid($restRequest->getHttpRequest()->headers->get(ShoppingListsRestApiConfig::X_COMPANY_USER_ID_HEADER_KEY));

        return $restShoppingListItemRequestTransfer;
    }
}
