<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList;

use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface;

class ShoppingListReader implements ShoppingListReaderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface
     */
    protected $shoppingListRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface
     */
    protected $customerRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface
     */
    protected $shoppingListRestResponseBuilder;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Reader\ShoppingListRestRequestReaderInterface $shoppingListRestRequestReaderInterface
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\Customer\Reader\CustomerRestRequestReaderInterface $customerRestRequestReader
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingList\Builder\ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilderInterface
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListRestRequestReaderInterface $shoppingListRestRequestReaderInterface,
        CustomerRestRequestReaderInterface $customerRestRequestReader,
        ShoppingListRestResponseBuilderInterface $shoppingListRestResponseBuilderInterface
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListRestRequestReader = $shoppingListRestRequestReaderInterface;
        $this->customerRestRequestReader = $customerRestRequestReader;
        $this->shoppingListRestResponseBuilder = $shoppingListRestResponseBuilderInterface;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerShoppingListCollection(RestRequestInterface $restRequest): RestResponseInterface
    {
        $customerResponseTransfer = $this->customerRestRequestReader->readCustomerResponseTransferFromRequest($restRequest);

        if ($customerResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $this->customerRestRequestReader->mapCustomerResponseErrorsToErrorsCodes(
                    $customerResponseTransfer->getErrors()->getArrayCopy()
                )
            );
        }

        $restShoppingListCollectionResponseTransfer = $this->shoppingListsRestApiClient->getCustomerShoppingListCollection(
            $customerResponseTransfer->getCustomerTransfer()
        );

        if (count($restShoppingListCollectionResponseTransfer->getErrorCodes()) > 0) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $restShoppingListCollectionResponseTransfer->getErrorCodes()
            );
        }

        return $this->shoppingListRestResponseBuilder->buildShoppingListCollectionRestResponse(
            $restShoppingListCollectionResponseTransfer
        );
    }

    /**
     * @param string $uuidShoppingList
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function getCustomerShoppingList(
        string $uuidShoppingList,
        RestRequestInterface $restRequest
    ): RestResponseInterface {
        $restShoppingListRequestTransfer = $this->shoppingListRestRequestReader->readRestShoppingListRequestTransferByUuid(
            $uuidShoppingList,
            $restRequest
        );

        if (count($restShoppingListRequestTransfer->getErrorCodes()) > 0) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $restShoppingListRequestTransfer->getErrorCodes()
            );
        }

        $shoppingListResponseTransfer = $this->shoppingListsRestApiClient->findShoppingListByUuid($restShoppingListRequestTransfer);

        if ($shoppingListResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $shoppingListResponseTransfer->getErrors()
            );
        }

        return $this->shoppingListRestResponseBuilder->buildShoppingListRestResponse(
            $shoppingListResponseTransfer->getShoppingList()
        );
    }
}
