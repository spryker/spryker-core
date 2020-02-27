<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\SalesReturn\Business\Validator;

use Generated\Shared\Transfer\CreateReturnRequestTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\OrderItemFilterTransfer;
use Generated\Shared\Transfer\ReturnResponseTransfer;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface;
use Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface;

class ReturnValidator implements ReturnValidatorInterface
{
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_REQUIRED_FIELD_ERROR = 'return.create_return.validation.error.required_item_fields';
    protected const GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR = 'return.create_return.validation.error.items';
    protected const GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR = 'return.create_return.validation.error.store';

    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface
     */
    protected $salesFacade;

    /**
     * @var \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface
     */
    protected $storeFacade;

    /**
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToSalesFacadeInterface $salesFacade
     * @param \Spryker\Zed\SalesReturn\Dependency\Facade\SalesReturnToStoreFacadeInterface $storeFacade
     */
    public function __construct(
        SalesReturnToSalesFacadeInterface $salesFacade,
        SalesReturnToStoreFacadeInterface $storeFacade
    ) {
        $this->salesFacade = $salesFacade;
        $this->storeFacade = $storeFacade;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    public function validateReturnRequest(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        $returnResponseTransfer = $this->validateStore($createReturnRequestTransfer);

        if (!$returnResponseTransfer->getIsSuccessful()) {
            return $returnResponseTransfer;
        }

        $returnResponseTransfer = $this->validateReturnItems($createReturnRequestTransfer);

        if (!$returnResponseTransfer->getIsSuccessful()) {
            return $returnResponseTransfer;
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function validateStore(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        $storeTransfer = $this->storeFacade->findStoreByName($createReturnRequestTransfer->getStore());

        if (!$storeTransfer) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_STORE_ERROR);
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function validateReturnItems(CreateReturnRequestTransfer $createReturnRequestTransfer): ReturnResponseTransfer
    {
        if (!$this->checkReturnItemRequirements($createReturnRequestTransfer)) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_ITEM_REQUIRED_FIELD_ERROR);
        }

        $orderItemFilterTransfer = $this->mapCreateReturnRequestTransferToOrderItemFilterTransfer(
            $createReturnRequestTransfer,
            new OrderItemFilterTransfer()
        );

        $itemTransfers = $this->salesFacade
            ->getOrderItems($orderItemFilterTransfer)
            ->getItems();

        if ($itemTransfers->count() !== $createReturnRequestTransfer->getReturnItems()->count()) {
            return $this->createErrorReturnResponse(static::GLOSSARY_KEY_CREATE_RETURN_ITEM_ERROR);
        }

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(true);
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     *
     * @return bool
     */
    protected function checkReturnItemRequirements(CreateReturnRequestTransfer $createReturnRequestTransfer): bool
    {
        foreach ($createReturnRequestTransfer->getReturnItems() as $returnItemTransfer) {
            $returnItemTransfer->requireOrderItem();
            $itemTransfer = $returnItemTransfer->getOrderItem();

            if (!$itemTransfer->getIdSalesOrderItem() && !$itemTransfer->getUuid()) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CreateReturnRequestTransfer $createReturnRequestTransfer
     * @param \Generated\Shared\Transfer\OrderItemFilterTransfer $orderItemFilterTransfer
     *
     * @return \Generated\Shared\Transfer\OrderItemFilterTransfer
     */
    protected function mapCreateReturnRequestTransferToOrderItemFilterTransfer(
        CreateReturnRequestTransfer $createReturnRequestTransfer,
        OrderItemFilterTransfer $orderItemFilterTransfer
    ): OrderItemFilterTransfer {
        $orderItemFilterTransfer->setCustomerReference(
            $createReturnRequestTransfer->getCustomer()->getCustomerReference()
        );

        foreach ($createReturnRequestTransfer->getReturnItems() as $returnItemTransfer) {
            $itemTransfer = $returnItemTransfer->getOrderItem();

            $orderItemFilterTransfer
                ->addSalesOrderItemId($itemTransfer->getIdSalesOrderItem())
                ->addSalesOrderItemUuid($itemTransfer->getUuid());
        }

        return $orderItemFilterTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\ReturnResponseTransfer
     */
    protected function createErrorReturnResponse(string $message): ReturnResponseTransfer
    {
        $messageTransfer = (new MessageTransfer())
            ->setValue($message);

        return (new ReturnResponseTransfer())
            ->setIsSuccessful(false)
            ->addMessage($messageTransfer);
    }
}
