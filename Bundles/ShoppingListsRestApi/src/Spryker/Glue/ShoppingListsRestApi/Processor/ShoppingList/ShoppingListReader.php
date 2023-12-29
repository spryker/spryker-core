<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\RestShoppingListCollectionResponseTransfer;
use Generated\Shared\Transfer\ShoppingListCollectionTransfer;
use Generated\Shared\Transfer\ShoppingListConditionsTransfer;
use Generated\Shared\Transfer\ShoppingListCriteriaTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToShoppingListClientInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\CustomerMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListRestResponseBuilderInterface;
use Spryker\Shared\ShoppingListsRestApi\ShoppingListsRestApiConfig;

class ShoppingListReader implements ShoppingListReaderInterface
{
    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\CustomerMapperInterface
     */
    protected $customerMapper;

    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToShoppingListClientInterface
     */
    protected $shoppingListClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListRestRequestReaderInterface
     */
    protected $shoppingListRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListRestResponseBuilderInterface
     */
    protected $shoppingListRestResponseBuilder;

    /**
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Mapper\CustomerMapperInterface $customerMapper
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Dependency\Client\ShoppingListsRestApiToShoppingListClientInterface $shoppingListClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestRequest\ShoppingListRestRequestReaderInterface $shoppingListRestRequestReader
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\RestResponseBuilder\ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilder
     */
    public function __construct(
        CustomerMapperInterface $customerMapper,
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListsRestApiToShoppingListClientInterface $shoppingListClient,
        ShoppingListRestRequestReaderInterface $shoppingListRestRequestReader,
        ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilder
    ) {
        $this->customerMapper = $customerMapper;
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListClient = $shoppingListClient;
        $this->shoppingListRestRequestReader = $shoppingListRestRequestReader;
        $this->shoppingListRestResponseBuilder = $shoppingListRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerShoppingListCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $customerTransfer = $this->customerMapper->mapRestUserTransferToCustomerTransfer(
            $restRequest->getRestUser(),
            new CustomerTransfer(),
        );

        $shoppingListCollectionTransfer = $this->getShoppingListCollectionForCustomer($customerTransfer);
        $restShoppingListCollectionResponseTransfer = (new RestShoppingListCollectionResponseTransfer())
            ->setShoppingLists($shoppingListCollectionTransfer->getShoppingLists());

        if (count($restShoppingListCollectionResponseTransfer->getErrorIdentifiers()) > 0) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponse(
                $restRequest,
                $restShoppingListCollectionResponseTransfer->getErrorIdentifiers(),
            );
        }

        return $this->shoppingListRestResponseBuilder->buildShoppingListCollectionRestResponse(
            $restShoppingListCollectionResponseTransfer,
        );
    }

    /**
     * @param string $uuidShoppingList
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerShoppingList(string $uuidShoppingList, RestRequestInterface $restRequest): RestResponseInterface
    {
        $shoppingListTransfer = $this->shoppingListRestRequestReader->readShoppingListTransferFromRequest(
            $restRequest,
        );

        $shoppingListResponseTransfer = $this->shoppingListClient->findShoppingListByUuid($shoppingListTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            $errors = $shoppingListResponseTransfer->getErrors() ?: [ShoppingListsRestApiConfig::ERROR_IDENTIFIER_SHOPPING_LIST_NOT_FOUND];

            return $this->shoppingListRestResponseBuilder->buildErrorRestResponse($restRequest, $errors);
        }

        return $this->shoppingListRestResponseBuilder->buildShoppingListRestResponse(
            $shoppingListResponseTransfer->getShoppingList(),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\ShoppingListCollectionTransfer
     */
    protected function getShoppingListCollectionForCustomer(CustomerTransfer $customerTransfer): ShoppingListCollectionTransfer
    {
        $shoppingListConditionsTransfer = (new ShoppingListConditionsTransfer())
            ->addCustomerReference($customerTransfer->getCustomerReference())
            ->setWithExcludedBlacklistedShoppingLists(true)
            ->setWithCustomerSharedShoppingLists(true)
            ->setWithBusinessUnitSharedShoppingLists(true)
            ->setWithShoppingListItems(true);

        $companyUserTransfer = $customerTransfer->getCompanyUserTransfer();

        if ($companyUserTransfer && $companyUserTransfer->getIdCompanyUser()) {
            $shoppingListConditionsTransfer->addIdCompanyUser($companyUserTransfer->getIdCompanyUser());
            $shoppingListConditionsTransfer->addIdBlacklistCompanyUser($companyUserTransfer->getIdCompanyUser());
            $shoppingListConditionsTransfer->addIdCompanyBusinessUnit($companyUserTransfer->getFkCompanyBusinessUnit());
        }

        $shoppingListCriteriaTransfer = (new ShoppingListCriteriaTransfer())->setShoppingListConditions($shoppingListConditionsTransfer);

        return $this->shoppingListClient->getShoppingListCollection($shoppingListCriteriaTransfer);
    }
}
