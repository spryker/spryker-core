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

class ShoppingListItemsResourceMapper implements ShoppingListItemsResourceMapperInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemTransfer
     */
    public function mapRestRequestToShoppingListItemTransfer(
        RestRequestInterface $restRequest,
        ShoppingListItemTransfer $shoppingListItemTransfer
    ): ShoppingListItemTransfer {
        $shoppingListItemTransfer->fromArray(
            $restRequest->getAttributesDataFromRequest(),
            true
        );

        $shoppingListItemTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());

        return $shoppingListItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ShoppingListItemTransfer $shoppingListItemTransfer
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer
     */
    public function mapShoppingListItemTransferToRestShoppingListItemAttributesTransfer(
        ShoppingListItemTransfer $shoppingListItemTransfer,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestShoppingListItemAttributesTransfer {
        return $restShoppingListItemAttributesTransfer->fromArray(
            $shoppingListItemTransfer->toArray(),
            true
        );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function mapRestRequestToRestShoppingListItemRequestTransfer(
        RestRequestInterface $restRequest,
        RestShoppingListItemRequestTransfer $restShoppingListItemRequestTransfer
    ): RestShoppingListItemRequestTransfer {
        $shoppingListItemTransfer = $this->mapRestRequestToShoppingListItemTransfer(
            $restRequest,
            new ShoppingListItemTransfer()
        );

        $restShoppingListItemRequestTransfer->setShoppingListItem($shoppingListItemTransfer)
            ->setCompanyUserUuid($restRequest->getHttpRequest()->headers->get(ShoppingListsRestApiConfig::X_COMPANY_USER_ID_HEADER_KEY));

        return $restShoppingListItemRequestTransfer;
    }
}
