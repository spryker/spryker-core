<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\RestShoppingListsAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ShoppingListRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListsAttributesTransfer|null $restShoppingListsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function readShoppingListTransferFromRequest(
        RestRequestInterface $restRequest,
        ?RestShoppingListsAttributesTransfer $restShoppingListsAttributesTransfer = null
    ): ShoppingListTransfer;
}
