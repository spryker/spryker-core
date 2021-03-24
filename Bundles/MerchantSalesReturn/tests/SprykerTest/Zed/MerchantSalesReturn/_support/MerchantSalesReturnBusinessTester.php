<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesReturn;

use Codeception\Actor;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use Generated\Shared\Transfer\ReturnItemTransfer;
use Generated\Shared\Transfer\SaveOrderTransfer;

/**
 * Inherited Methods
 *
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @method \Spryker\Zed\MerchantSalesReturn\Business\MerchantSalesReturnFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantSalesReturnBusinessTester extends Actor
{
    use _generated\MerchantSalesReturnBusinessTesterActions;

    /**
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param string $stateMachine
     *
     * @return \Generated\Shared\Transfer\SaveOrderTransfer
     */
    public function getSaveOrderTransfer(MerchantTransfer $merchantTransfer, string $stateMachine): SaveOrderTransfer
    {
        $this->configureTestStateMachine([$stateMachine]);

        return $this->haveOrder([
            ItemTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
            ItemTransfer::UNIT_PRICE => 100,
            ItemTransfer::SUM_PRICE => 100,
        ], $stateMachine);
    }

    /**
     * @param string $merchantReference
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function createItemTransfer(string $merchantReference, int $idSalesOrder): ItemTransfer
    {
        $itemTransfer = new ItemTransfer();

        if ($merchantReference) {
            $itemTransfer->setMerchantReference($merchantReference);
        }

        if ($idSalesOrder) {
            $itemTransfer->setFkSalesOrder($idSalesOrder);
        }

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrderWithRelatedData(
        SaveOrderTransfer $saveOrderTransfer,
        MerchantTransfer $merchantTransfer
    ): MerchantOrderTransfer {
        $merchantOrderReference = $this->createMerchantOrderReference($saveOrderTransfer, $merchantTransfer);
        $merchantOrderTransfer = $this->haveMerchantOrder([
            MerchantOrderTransfer::MERCHANT_ORDER_REFERENCE => $merchantOrderReference,
            MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        foreach ($saveOrderTransfer->getOrderItems() as $itemTransfer) {
            $merchantOrderItemTransfer = $this->haveMerchantOrderItem([
                MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
                MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
            ]);

            $merchantOrderTransfer->addMerchantOrderItem($merchantOrderItemTransfer);
        }

        $merchantOrderTotalsTransfer = $this->haveMerchantOrderTotals($merchantOrderTransfer->getIdMerchantOrder());
        $merchantOrderTransfer->setTotals($merchantOrderTotalsTransfer);

        return $merchantOrderTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     *
     * @return string
     */
    public function createMerchantOrderReference(
        SaveOrderTransfer $saveOrderTransfer,
        MerchantTransfer $merchantTransfer
    ): string {
        return sprintf(
            '%s--%s',
            $saveOrderTransfer->getOrderReference(),
            $merchantTransfer->getMerchantReference()
        );
    }

    /**
     * @param string $merchantReference
     * @param int $idSalesOrder
     *
     * @return \Generated\Shared\Transfer\ReturnItemTransfer
     */
    public function createReturnItem(string $merchantReference, int $idSalesOrder): ReturnItemTransfer
    {
        return (new ReturnItemTransfer())
            ->setOrderItem($this->createItemTransfer($merchantReference, $idSalesOrder));
    }
}
