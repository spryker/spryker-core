<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\RestShoppingListRequestAttributesTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ShoppingListRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListRequestAttributesTransfer $restShoppingListRequestAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListTransfer
     */
    public function readShoppingListTransferFromRequest(
        RestRequestInterface $restRequest,
        ?RestShoppingListRequestAttributesTransfer $restShoppingListRequestAttributesTransfer = null
    ): ShoppingListTransfer;
}
