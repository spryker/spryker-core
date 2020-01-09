<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem;

use Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer;
use Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface;
use Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface;
use Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Builder\ShoppingListItemRestResponseBuilderInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface;
use Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Reader\ShoppingListItemRestRequestReaderInterface;

class ShoppingListItemAdder implements ShoppingListItemAdderInterface
{
    /**
     * @var \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface
     */
    protected $shoppingListsRestApiClient;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface
     */
    protected $shoppingListItemMapper;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Reader\ShoppingListItemRestRequestReaderInterface
     */
    protected $shoppingListItemRestRequestReader;

    /**
     * @var \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Builder\ShoppingListItemRestResponseBuilderInterface
     */
    protected $shoppingListItemRestResponseBuilder;

    /**
     * @param \Spryker\Client\ShoppingListsRestApi\ShoppingListsRestApiClientInterface $shoppingListsRestApiClient
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Mapper\ShoppingListItemMapperInterface $shoppingListItemMapper
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Reader\ShoppingListItemRestRequestReaderInterface $shoppingListItemRestRequestReader
     * @param \Spryker\Glue\ShoppingListsRestApi\Processor\ShoppingListItem\Builder\ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
     */
    public function __construct(
        ShoppingListsRestApiClientInterface $shoppingListsRestApiClient,
        ShoppingListItemMapperInterface $shoppingListItemMapper,
        ShoppingListItemRestRequestReaderInterface $shoppingListItemRestRequestReader,
        ShoppingListItemRestResponseBuilderInterface $shoppingListItemRestResponseBuilder
    ) {
        $this->shoppingListsRestApiClient = $shoppingListsRestApiClient;
        $this->shoppingListItemMapper = $shoppingListItemMapper;
        $this->shoppingListItemRestRequestReader = $shoppingListItemRestRequestReader;
        $this->shoppingListItemRestResponseBuilder = $shoppingListItemRestResponseBuilder;
    }

    /**
     * @param \Spryker\Glue\GlueApplication\Rest\Request\Data\RestRequestInterface $restRequest
     * @param \Generated\Shared\Transfer\RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
     *
     * @return \Spryker\Glue\GlueApplication\Rest\JsonApi\RestResponseInterface
     */
    public function addShoppingListItem(
        RestRequestInterface $restRequest,
        RestShoppingListItemAttributesTransfer $restShoppingListItemAttributesTransfer
    ): RestResponseInterface {
        $restShoppingListItemRequestTransfer = $this->shoppingListItemRestRequestReader->readRestShoppingListItemRequestTransferFromRequest(
            $restRequest
        );

        if (count($restShoppingListItemRequestTransfer->getErrorCodes()) > 0) {
            return $this->shoppingListItemRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $restShoppingListItemRequestTransfer->getErrorCodes()
            );
        }

        $restShoppingListItemRequestTransfer = $this->shoppingListItemMapper->mapRestShoppingListItemAttributesTransferToRestShoppingListItemRequestTransfer(
            $restShoppingListItemAttributesTransfer,
            $restShoppingListItemRequestTransfer
        );

        $shoppingListItemResponseTransfer = $this->shoppingListsRestApiClient->addShoppingListItem($restShoppingListItemRequestTransfer);

        if ($shoppingListItemResponseTransfer->getIsSuccess() === false) {
            return $this->shoppingListItemRestResponseBuilder->buildErrorRestResponseBasedOnErrorCodes(
                $shoppingListItemResponseTransfer->getErrors()
            );
        }

        return $this->shoppingListItemRestResponseBuilder->buildShoppingListItemRestResponse(
            $shoppingListItemResponseTransfer->getShoppingListItem(),
            $restShoppingListItemRequestTransfer->getShoppingListUuid()
        );
    }
}
