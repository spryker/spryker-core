<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Condition;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayoutReversal;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Condition\IsMerchantPayoutReversedConditionPlugin;
use SprykerTest\Zed\SalesPaymentMerchant\SalesPaymentMerchantCommunicationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group SalesPaymentMerchant
 * @group Communication
 * @group Plugin
 * @group Oms
 * @group Condition
 * @group IsMerchantRefundedConditionPluginTest
 * Add your own group annotations below this line
 */
class IsMerchantRefundedConditionPluginTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\SalesPaymentMerchant\SalesPaymentMerchantCommunicationTester $tester
     */
    protected SalesPaymentMerchantCommunicationTester $tester;

    /**
     * @return void
     */
    public function testCheckReturnsTrueWhenSalesOrderItemWasPaidOutToMerchant(): void
    {
        //Arrange
        $condition = new IsMerchantPayoutReversedConditionPlugin();
        $merchantReference = Uuid::uuid4()->toString();
        $transferId = Uuid::uuid4()->toString();
        $orderItemReference = Uuid::uuid4()->toString();

        $salesOrderItemEntity = (new SpySalesOrderItem())
            ->setMerchantReference($merchantReference)
            ->setOrderItemReference($orderItemReference);

        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();
        $this->tester->haveMerchant([MerchantTransfer::MERCHANT_REFERENCE => $merchantReference]);

        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemEntity], $merchantReference);
        $salesOrderItemEntity = $salesOrderEntity->getItems()->getIterator()->current();

        $salesPaymentMerchantPayoutReversal = $this->tester->haveSalesPaymentMerchantPayoutReversalPersisted(
            [
                'transfer_id' => $transferId,
                'merchant_reference' => $merchantReference,
                'order_reference' => $salesOrderEntity->getOrderReference(),
                'item_references' => $orderItemReference,
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $salesOrderEntity->addSpySalesPaymentMerchantPayoutReversal($salesPaymentMerchantPayoutReversal);

        //Act
        $this->assertTrue($condition->check($salesOrderItemEntity));
    }

    /**
     * @return void
     */
    public function testCheckReturnsFalseWhenSalesOrderItemWasNotPaidOutToMerchant(): void
    {
        //Arrange
        $condition = new IsMerchantPayoutReversedConditionPlugin();

        $salesPaymentMerchantPayoutReversal = (new SpySalesPaymentMerchantPayoutReversal())
            ->setMerchantReference('merchant-reference')
            ->setItemReferences('other-order-item-reference')
            ->setIsSuccessful(false);

        $salesOrderItemEntity = (new SpySalesOrderItem())
            ->setMerchantReference('merchant-reference')
            ->setOrderItemReference('order-item-reference');

        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();

        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemEntity]);
        $salesOrderEntity->addSpySalesPaymentMerchantPayoutReversal($salesPaymentMerchantPayoutReversal);

        $salesOrderItemEntity->setOrder($salesOrderEntity);

        //Act
        $this->assertFalse($condition->check($salesOrderItemEntity));
    }

    /**
     * @return void
     */
    public function testCheckReturnsTrueWhenPaymentMethodDoesNotSupportMerchantPayouts(): void
    {
        //Arrange
        $condition = new IsMerchantPayoutReversedConditionPlugin();

        $salesPaymentMerchantPayoutReversal = (new SpySalesPaymentMerchantPayoutReversal())
            ->setMerchantReference('merchant-reference')
            ->setItemReferences('other-order-item-reference')
            ->setIsSuccessful(false);

        $salesOrderItemEntity = (new SpySalesOrderItem())
            ->setMerchantReference('merchant-reference')
            ->setOrderItemReference('order-item-reference');

        // PaymentMethod does not have paymentMethodAppConfiguration
        $this->tester->havePaymentProviderWithPaymentMethod();

        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemEntity]);
        $salesOrderEntity->addSpySalesPaymentMerchantPayoutReversal($salesPaymentMerchantPayoutReversal);

        $salesOrderItemEntity->setOrder($salesOrderEntity);

        //Act
        $this->assertTrue($condition->check($salesOrderItemEntity));
    }
}
