<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CustomerDiscountConnector\Business\Saver;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorEntityManagerInterface;
use Spryker\Zed\PropelOrm\Business\Transaction\DatabaseTransactionHandlerTrait;

class CustomerDiscountSaver implements CustomerDiscountSaverInterface
{
    use DatabaseTransactionHandlerTrait;

    /**
     * @var \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorEntityManagerInterface
     */
    protected $entityManager;

    /**
     * @param \Spryker\Zed\CustomerDiscountConnector\Persistence\CustomerDiscountConnectorEntityManagerInterface $entityManager
     */
    public function __construct(CustomerDiscountConnectorEntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    public function saveCustomerDiscounts(QuoteTransfer $quoteTransfer): void
    {
        $this->handleDatabaseTransaction(function () use ($quoteTransfer): void {
            $this->executeCustomerDiscountSaveTransaction($quoteTransfer);
        });
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function executeCustomerDiscountSaveTransaction(QuoteTransfer $quoteTransfer): void
    {
        if (!$quoteTransfer->getCustomer() || !$quoteTransfer->getCustomer()->getIdCustomer()) {
            return;
        }

        $idCustomer = $quoteTransfer->getCustomer()->getIdCustomer();

        $this->saveOrderDiscounts($idCustomer, $quoteTransfer);
    }

    /**
     * @param int $idCustomer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return void
     */
    protected function saveOrderDiscounts(int $idCustomer, QuoteTransfer $quoteTransfer): void
    {
        $discountTransfers = $quoteTransfer->getVoucherDiscounts()->getArrayCopy();
        $cartRuleDiscounts = $quoteTransfer->getCartRuleDiscounts()->getArrayCopy();
        $discountTransfers = array_merge($discountTransfers, $cartRuleDiscounts);

        if (count($discountTransfers) === 0) {
            return;
        }

        $discountIds = [];
        foreach ($discountTransfers as $discountTransfer) {
            $discountIds[] = $discountTransfer->getIdDiscount();
        }

        $this->entityManager->createCustomerDiscounts($idCustomer, $discountIds);
    }
}
