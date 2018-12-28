<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\Request;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListItemRequestTransfer;
use Generated\Shared\Transfer\RestShoppingListRequestTransfer;
use Generated\Shared\Transfer\ShoppingListItemTransfer;
use Generated\Shared\Transfer\ShoppingListTransfer;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\ShoppingListsRestApiConfig;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig as SharedShoppingListsRestApiConfig;

class RestRequestReader implements RestRequestReaderInterface
{
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
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function readCustomerResponseTransferFromRequest(RestRequestInterface $restRequest): CustomerResponseTransfer
    {
        $companyUserUuid = $restRequest->getHttpRequest()->headers->get(SharedShoppingListsRestApiConfig::X_COMPANY_USER_ID_HEADER_KEY);

        if (!$companyUserUuid) {
            return (new CustomerResponseTransfer())->addError(
                (new CustomerErrorTransfer())
                    ->setMessage(SharedShoppingListsRestApiConfig::RESPONSE_CODE_X_COMPANY_USER_ID_HEADER_KEY_NOT_SPECIFIED)
            );
        }

        $customerTransfer = (new CustomerTransfer())
            ->setCompanyUserTransfer(
                (new CompanyUserTransfer())
                    ->setUuid($companyUserUuid)
            );

        if ($restRequest->getUser()) {
            $customerTransfer->setCustomerReference($restRequest->getUser()->getNaturalIdentifier());
        }

        return (new CustomerResponseTransfer())->setCustomerTransfer($customerTransfer);
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListRequestTransfer
     */
    public function readRestShoppingListRequestTransferFromRequest(
        RestRequestInterface $restRequest
    ): RestShoppingListRequestTransfer {
        $customerResponseTransfer = $this->readCustomerResponseTransferFromRequest($restRequest);

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return (new RestShoppingListRequestTransfer())->setErrors(
                $this->mapCustomerResponseErrorsToErrorsCodes($customerResponseTransfer->getErrors()->getArrayCopy())
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
    public function readRestShoppingListRequestTransferWithUuidFromRequest(
        ?string $uuidShoppingList,
        RestRequestInterface $restRequest
    ): RestShoppingListRequestTransfer {
        if (!$uuidShoppingList) {
            return (new RestShoppingListRequestTransfer())->addError(
                SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ID_NOT_SPECIFIED
            );
        }

        $restShoppingListRequestTransfer = $this->readRestShoppingListRequestTransferFromRequest($restRequest);

        if (count($restShoppingListRequestTransfer->getErrors()) > 0) {
            return $restShoppingListRequestTransfer;
        }

        $restShoppingListRequestTransfer->getShoppingList()->setUuid($uuidShoppingList);

        return $restShoppingListRequestTransfer;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferFromRequest(
        RestRequestInterface $restRequest
    ): RestShoppingListItemRequestTransfer {
        $uuidShoppingList = $this->readParentUuidShoppingList($restRequest);
        $restShoppingListRequestTransfer = $this->readRestShoppingListRequestTransferWithUuidFromRequest(
            $uuidShoppingList,
            $restRequest
        );

        if (count($restShoppingListRequestTransfer->getErrors()) > 0) {
            return (new RestShoppingListItemRequestTransfer())->setErrors(
                $restShoppingListRequestTransfer->getErrors()
            );
        }

        return (new RestShoppingListItemRequestTransfer())
            ->setShoppingListUuid($restShoppingListRequestTransfer->getShoppingList()->getUuid())
            ->setCompanyUserUuid($restShoppingListRequestTransfer->getCompanyUserUuid())
            ->setShoppingListItem(
                (new ShoppingListItemTransfer())
                    ->setCustomerReference($restShoppingListRequestTransfer->getCustomerReference())
            );
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Generated\Shared\Transfer\RestShoppingListItemRequestTransfer
     */
    public function readRestShoppingListItemRequestTransferWithUuidFromRequest(
        RestRequestInterface $restRequest
    ): RestShoppingListItemRequestTransfer {
        $uuidShoppingListItem = $restRequest->getResource()->getId();
        if (!$uuidShoppingListItem) {
            return (new RestShoppingListItemRequestTransfer())->addError(
                SharedShoppingListsRestApiConfig::RESPONSE_CODE_SHOPPING_LIST_ITEM_ID_NOT_SPECIFIED
            );
        }

        $restShoppingListItemRequestTransfer = $this->readRestShoppingListItemRequestTransferFromRequest($restRequest);

        if (count($restShoppingListItemRequestTransfer->getErrors()) > 0) {
            return $restShoppingListItemRequestTransfer;
        }

        $restShoppingListItemRequestTransfer->getShoppingListItem()->setUuid($uuidShoppingListItem);

        return $restShoppingListItemRequestTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerErrorTransfer[] $errors
     *
     * @return string[]
     */
    public function mapCustomerResponseErrorsToErrorsCodes(array $errors): array
    {
        $errorCodes = [];

        foreach ($errors as $error) {
            $errorCodes[] = $error->getMessage();
        }

        return $errorCodes;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return string|null
     */
    protected function readParentUuidShoppingList(RestRequestInterface $restRequest): ?string
    {
        $shoppingListResource = $restRequest->findParentResourceByType(ShoppingListsRestApiConfig::RESOURCE_SHOPPING_LISTS);

        if ($shoppingListResource !== null) {
            return $shoppingListResource->getId();
        }

        return null;
    }
}
