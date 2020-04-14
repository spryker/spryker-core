<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\MerchantSalesOrder;

use Codeception\Actor;
use Generated\Shared\DataBuilder\ItemBuilder;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MerchantOrderItemTransfer;
use Generated\Shared\Transfer\MerchantOrderTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
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
 * @method \Spryker\Zed\MerchantSalesOrder\Business\MerchantSalesOrderFacadeInterface getFacade()
 *
 * @SuppressWarnings(PHPMD)
 */
class MerchantSalesOrderBusinessTester extends Actor
{
    use _generated\MerchantSalesOrderBusinessTesterActions;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\ProductAbstractStorageTransfer
     */
    public function getItemTransfer(array $seedData = []): ItemTransfer
    {
        return (new ItemBuilder($seedData))->build();
    }

    /**
     * @param string $orderReference
     * @param string $merchantReference
     *
     * @return string
     */
    public function getMerchantOrderReference(string $orderReference, string $merchantReference): string
    {
        return sprintf(
            '%s--%s',
            $orderReference,
            $merchantReference
        );
    }

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
     * @param \Generated\Shared\Transfer\SaveOrderTransfer $saveOrderTransfer
     * @param \Generated\Shared\Transfer\MerchantTransfer $merchantTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $merchantOrderReference
     *
     * @return \Generated\Shared\Transfer\MerchantOrderTransfer
     */
    public function createMerchantOrderWithRelatedData(
        SaveOrderTransfer $saveOrderTransfer,
        MerchantTransfer $merchantTransfer,
        ItemTransfer $itemTransfer,
        string $merchantOrderReference
    ): MerchantOrderTransfer {
        $merchantOrderTransfer = $this->haveMerchantOrder([
            MerchantOrderTransfer::MERCHANT_ORDER_REFERENCE => $merchantOrderReference,
            MerchantOrderTransfer::ID_ORDER => $saveOrderTransfer->getIdSalesOrder(),
            MerchantOrderTransfer::MERCHANT_REFERENCE => $merchantTransfer->getMerchantReference(),
        ]);

        $merchantOrderItemTransfer = $this->haveMerchantOrderItem([
            MerchantOrderItemTransfer::ID_ORDER_ITEM => $itemTransfer->getIdSalesOrderItem(),
            MerchantOrderItemTransfer::ID_MERCHANT_ORDER => $merchantOrderTransfer->getIdMerchantOrder(),
        ]);
        $merchantOrderTransfer->addMerchantOrderItem($merchantOrderItemTransfer);

        $merchantOrderTotalsTransfer = $this->haveMerchantOrderTotals($merchantOrderTransfer->getIdMerchantOrder());
        $merchantOrderTransfer->setTotals($merchantOrderTotalsTransfer);

        return $merchantOrderTransfer;
    }
}
