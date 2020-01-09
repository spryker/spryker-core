<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader;

use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;

class ShoppingListRestRequestReader implements ShoppingListRestRequestReaderInterface
{
    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface
     */
    protected $customerRestRequestReader;

    /**
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface $customerRestRequestReader
     */
    public function __construct(
        CustomerRestRequestReaderInterface $customerRestRequestReader
    ) {
        $this->customerRestRequestReader = $customerRestRequestReader;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    public function readUuidShoppingList(RestRequestInterface $restRequest): ?string
    {
        return $restRequest->getResource()->getId();
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function readRestShoppingListRequestTransferFromRequest(
        RestRequestInterface $restRequest
    ): RestShoppingListRequestTransfer {
        $customerResponseTransfer = $this->customerRestRequestReader->readCustomerResponseTransferFromRequest($restRequest);

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return (new RestShoppingListRequestTransfer())->setErrorCodes(
                $this->customerRestRequestReader->mapCustomerResponseErrorsToErrorsCodes($customerResponseTransfer->getErrors()->getArrayCopy())
            );
        }

        return (new RestShoppingListRequestTransfer())
            ->setShoppingList(new ShoppingListTransfer())
            ->setCustomerReference($customerResponseTransfer->getCustomerTransfer()->getCustomerReference())
            ->setCompanyUserUuid($customerResponseTransfer->getCustomerTransfer()->getCompanyUserTransfer()->getUuid());
    }

    /**
     * @param string|null $uuidShoppingList
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function readRestShoppingListRequestTransferByUuid(
        ?string $uuidShoppingList,
        RestRequestInterface $restRequest
    ): RestShoppingListRequestTransfer {
        if (!$uuidShoppingList) {
            return (new RestShoppingListRequestTransfer())->addErrorCode(
                SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED
            );
        }

        $restShoppingListRequestTransfer = $this->readRestShoppingListRequestTransferFromRequest($restRequest);

        if (count($restShoppingListRequestTransfer->getErrorCodes()) > 0) {
            return $restShoppingListRequestTransfer;
        }

        $restShoppingListRequestTransfer->getShoppingList()->setUuid($uuidShoppingList);

        return $restShoppingListRequestTransfer;
    }
}
