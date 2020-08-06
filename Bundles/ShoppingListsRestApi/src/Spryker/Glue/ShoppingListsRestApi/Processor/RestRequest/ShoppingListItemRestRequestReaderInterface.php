<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\ShoppingListItemRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ShoppingListItemRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemRequestTransfer
     */
    public function readShoppingListItemRequestTransferFromRequest(RestRequestInterface $restRequest): ShoppingListItemRequestTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\ShoppingListItemRequestTransfer
     */
    public function readShoppingListItemRequestTransferByUuid(RestRequestInterface $restRequest): ShoppingListItemRequestTransfer;
}
