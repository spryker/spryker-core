<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Request;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;

interface RestRequestReaderInterface
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
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function readCustomerResponseTransferFromRequest(
        RestRequestInterface $restRequest
    ): CustomerResponseTransfer;

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
    public function readRestShoppingListRequestTransferWithUuidFromRequest(
        ?string $uuidShoppingList,
        RestRequestInterface $restRequest
    ): RestShoppingListRequestTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferFromRequest(
        RestRequestInterface $restRequest
    ): RestShoppingListItemRequestTransfer;

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferWithUuidFromRequest(
        RestRequestInterface $restRequest
    ): RestShoppingListItemRequestTransfer;

    /**
     * @param \Generated\Shared\Transfer\CustomerErrorTransfer[] $errors
     *
     * @return string[]
     */
    public function mapCustomerResponseErrorsToErrorsCodes(array $errors): array;
}
