<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\SalesReturnsRestApi\Processor\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemCollectionTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\RestErrorMessageTransfer;
use Generated\Shared\Transfer\RestOrderItemsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnItemsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnsAttributesTransfer;
use Generated\Shared\Transfer\RestReturnTotalsAttributesTransfer;
use Generated\Shared\Transfer\ReturnTransfer;
use Spryker\Glue\SalesReturnsRestApi\Dependency\RestApiResource\SalesReturnsRestApiToOrdersRestApiResourceInterface;
use Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig;

class ReturnResourceMapper implements ReturnResourceMapperInterface
{
    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig
     */
    protected $salesReturnsRestApiConfig;

    /**
     * @var \Spryker\Glue\SalesReturnsRestApi\Dependency\RestApiResource\SalesReturnsRestApiToOrdersRestApiResourceInterface
     */
    protected $ordersRestApiResource;

    /**
     * @param \Spryker\Glue\SalesReturnsRestApi\SalesReturnsRestApiConfig $salesReturnsRestApiConfig
     * @param \Spryker\Glue\SalesReturnsRestApi\Dependency\RestApiResource\SalesReturnsRestApiToOrdersRestApiResourceInterface $ordersRestApiResource
     */
    public function __construct(
        SalesReturnsRestApiConfig $salesReturnsRestApiConfig,
        SalesReturnsRestApiToOrdersRestApiResourceInterface $ordersRestApiResource
    ) {
        $this->salesReturnsRestApiConfig = $salesReturnsRestApiConfig;
        $this->ordersRestApiResource = $ordersRestApiResource;
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     * @param \Generated\Shared\Transfer\RestErrorMessageTransfer $restErrorMessageTransfer
     *
     * @return \Generated\Shared\Transfer\RestErrorMessageTransfer
     */
    public function mapMessageTransferToRestErrorMessageTransfer(
        MessageTransfer $messageTransfer,
        RestErrorMessageTransfer $restErrorMessageTransfer
    ): RestErrorMessageTransfer {
        $errorIdentifier = $messageTransfer->getValue();
        $errorIdentifierToRestErrorMapping = $this->salesReturnsRestApiConfig->getErrorIdentifierToRestErrorMapping();

        if ($errorIdentifier && isset($errorIdentifierToRestErrorMapping[$errorIdentifier])) {
            return $restErrorMessageTransfer->fromArray($errorIdentifierToRestErrorMapping[$errorIdentifier], true);
        }

        return $restErrorMessageTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ReturnTransfer $returnTransfer
     * @param \Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer
     *
     * @return \Generated\Shared\Transfer\RestReturnDetailsAttributesTransfer
     */
    public function mapReturnTransferToRestReturnDetailsAttributesTransfer(
        ReturnTransfer $returnTransfer,
        RestReturnDetailsAttributesTransfer $restReturnDetailsAttributesTransfer
    ): RestReturnDetailsAttributesTransfer {
        $returnTransfer
            ->requireReturnTotals()
            ->requireReturnItems();

        $restReturnItemsAttributesTransfers = $this->mapReturnItemTransfersRestReturnItemsAttributesTransfers($returnTransfer->getReturnItems());

        $restReturnDetailsAttributesTransfer = $restReturnDetailsAttributesTransfer
            ->fromArray($returnTransfer->toArray(), true)
            ->setReturnItems(new ArrayObject($restReturnItemsAttributesTransfers))
            ->setReturnTotals(
                (new RestReturnTotalsAttributesTransfer())
                    ->fromArray($returnTransfer->getReturnTotals()->toArray(), true)
            );

        return $restReturnDetailsAttributesTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ReturnTransfer[] $returnTransfers
     *
     * @return \Generated\Shared\Transfer\RestReturnsAttributesTransfer[]
     */
    public function mapReturnTransfersToRestReturnsAttributesTransfers(ArrayObject $returnTransfers): array
    {
        $restReturnsAttributesTransfers = [];

        foreach ($returnTransfers as $returnTransfer) {
            $restReturnsAttributesTransfers[] = (new RestReturnsAttributesTransfer())
                ->fromArray($returnTransfer->toArray(), true)
                ->setReturnTotals(
                    (new RestReturnTotalsAttributesTransfer())
                        ->fromArray($returnTransfer->getReturnTotals()->toArray(), true)
                );
        }

        return $restReturnsAttributesTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemCollectionTransfer $itemCollectionTransfer
     *
     * @return \Generated\Shared\Transfer\RestOrderItemsAttributesTransfer[]
     */
    public function mapItemCollectionTransferToRestOrderItemsAttributesTransfers(ItemCollectionTransfer $itemCollectionTransfer): array
    {
        $restReturnItemsAttributesTransfer = [];

        foreach ($itemCollectionTransfer->getItems() as $itemTransfer) {
            $restReturnItemsAttributesTransfer[] = $this->ordersRestApiResource->mapItemTransferToRestOrderItemsAttributesTransfer(
                $itemTransfer,
                new RestOrderItemsAttributesTransfer()
            );
        }

        return $restReturnItemsAttributesTransfer;
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\ReturnItemTransfer[] $returnItemTransfers
     *
     * @return \Generated\Shared\Transfer\RestReturnItemsAttributesTransfer[]
     */
    protected function mapReturnItemTransfersRestReturnItemsAttributesTransfers(ArrayObject $returnItemTransfers): array
    {
        $restReturnItemsAttributesTransfers = [];

        foreach ($returnItemTransfers as $returnItemTransfer) {
            $restOrderItemsAttributesTransfer = $this->ordersRestApiResource->mapItemTransferToRestOrderItemsAttributesTransfer(
                $returnItemTransfer->getOrderItem(),
                new RestOrderItemsAttributesTransfer()
            );

            $restReturnItemsAttributesTransfers[] = (new RestReturnItemsAttributesTransfer())
                ->fromArray($returnItemTransfer->toArray(), true)
                ->setOrderItem($restOrderItemsAttributesTransfer);
        }

        return $restReturnItemsAttributesTransfers;
    }
}
