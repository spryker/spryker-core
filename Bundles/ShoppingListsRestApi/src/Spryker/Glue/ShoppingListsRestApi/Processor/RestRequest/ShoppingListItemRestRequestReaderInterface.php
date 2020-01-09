<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest;

use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ShoppingListItemRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferFromRequest(RestRequestInterface $restRequest): RestShoppingListItemRequestTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferByUuid(RestRequestInterface $restRequest): RestShoppingListItemRequestTransfer;
}
