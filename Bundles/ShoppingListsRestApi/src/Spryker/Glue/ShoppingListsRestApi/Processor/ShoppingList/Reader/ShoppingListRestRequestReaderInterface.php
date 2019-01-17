<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader;

use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface ShoppingListRestRequestReaderInterface
{
    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    public function readUuidShoppingList(RestRequestInterface $restRequest): ?string;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function readRestShoppingListRequestTransferFromRequest(
        RestRequestInterface $restRequest
    ): RestShoppingListRequestTransfer;

    /**
     * @param string|null $uuidShoppingList
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function readRestShoppingListRequestTransferByUuid(
        ?string $uuidShoppingList,
        RestRequestInterface $restRequest
    ): RestShoppingListRequestTransfer;
}
