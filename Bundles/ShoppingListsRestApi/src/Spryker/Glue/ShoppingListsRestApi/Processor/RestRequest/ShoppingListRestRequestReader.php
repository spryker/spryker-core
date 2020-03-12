<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\ShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface;

class ShoppingListRestRequestReader implements ShoppingListRestRequestReaderInterface
{
    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface
     */
    protected $shoppingListResourceMapper;

    /**
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\ShoppingListMapperInterface $shoppingListResourceMapper
     */
    public function __construct(ShoppingListMapperInterface $shoppingListResourceMapper)
    {
        $this->shoppingListResourceMapper = $shoppingListResourceMapper;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\ShoppingListRequestTransfer|null $shoppingListRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function readShoppingListTransferFromRequest(
        RestRequestInterface $restRequest,
        ?ShoppingListRequestTransfer $shoppingListRequestTransfer = null
    ): ShoppingListTransfer {
        $shoppingListTransfer = new ShoppingListTransfer();
        $shoppingListTransfer->setUuid($restRequest->getResource()->getId())
            ->setIdCompanyUser($restRequest->getRestUser()->getIdCompanyUser())
            ->setCustomerReference($restRequest->getRestUser()->getNaturalIdentifier());
        if (!$shoppingListRequestTransfer) {
            return $shoppingListTransfer;
        }

        return $this->shoppingListResourceMapper->mapRestShoppingListsAttributesTransferToShoppingListTransfer(
            $shoppingListRequestTransfer,
            $shoppingListTransfer
        );
    }
}
