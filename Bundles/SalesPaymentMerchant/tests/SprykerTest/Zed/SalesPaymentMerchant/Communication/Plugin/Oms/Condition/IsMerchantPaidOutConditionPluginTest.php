<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Condition;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\MerchantTransfer;
use Orm\Zed\Sales\Persistence\SpySalesOrderItem;
use Orm\Zed\SalesPaymentMerchant\Persistence\SpySalesPaymentMerchantPayout;
use Ramsey\Uuid\Uuid;
use Spryker\Zed\SalesPaymentMerchant\Communication\Plugin\Oms\Condition\IsMerchantPaidOutConditionPlugin;
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
 * @group IsMerchantPaidOutConditionPluginTest
 * Add your own group annotations below this line
 */
class IsMerchantPaidOutConditionPluginTest extends Unit
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
        $condition = new IsMerchantPaidOutConditionPlugin();
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

        $salesPaymentMerchantPayoutEntity = $this->tester->haveSalesPaymentMerchantPayoutPersisted(
            [
                'transfer_id' => $transferId,
                'merchant_reference' => $merchantReference,
                'order_reference' => $salesOrderEntity->getOrderReference(),
                'item_references' => $orderItemReference,
                'is_successful' => true,
                'amount' => 900,
            ],
        );

        $salesOrderEntity->addSpySalesPaymentMerchantPayout($salesPaymentMerchantPayoutEntity);

        //Assert
        $this->assertTrue($condition->check($salesOrderItemEntity));
    }

    /**
     * @return void
     */
    public function testCheckReturnsFalseWhenSalesOrderItemWasNotPaidOutToMerchant(): void
    {
        //Arrange
        $condition = new IsMerchantPaidOutConditionPlugin();

        $salesPaymentMerchantPayoutEntity = (new SpySalesPaymentMerchantPayout())
            ->setMerchantReference('merchant-reference')
            ->setItemReferences('other-order-item-reference')
            ->setIsSuccessful(false);

        $salesOrderItemEntity = (new SpySalesOrderItem())
            ->setMerchantReference('merchant-reference')
            ->setOrderItemReference('order-item-reference');

        $this->tester->havePaymentProviderWithPaymentMethodSupportingPayouts();

        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemEntity]);
        $salesOrderEntity->addSpySalesPaymentMerchantPayout($salesPaymentMerchantPayoutEntity);

        $salesOrderItemEntity->setOrder($salesOrderEntity);

        //Assert
        $this->assertFalse($condition->check($salesOrderItemEntity));
    }

    /**
     * @return void
     */
    public function testCheckReturnsTrueWhenPaymentMethodDoesNotSupportMerchantPayouts(): void
    {
        //Arrange
        $condition = new IsMerchantPaidOutConditionPlugin();

        $salesPaymentMerchantPayoutEntity = (new SpySalesPaymentMerchantPayout())
            ->setMerchantReference('merchant-reference')
            ->setItemReferences('other-order-item-reference')
            ->setIsSuccessful(false);

        $salesOrderItemEntity = (new SpySalesOrderItem())
            ->setMerchantReference('merchant-reference')
            ->setOrderItemReference('order-item-reference');

        $this->tester->havePaymentProviderWithPaymentMethod();

        $salesOrderEntity = $this->tester->mockSalesOrderEntity([$salesOrderItemEntity]);
        $salesOrderEntity->addSpySalesPaymentMerchantPayout($salesPaymentMerchantPayoutEntity);

        $salesOrderItemEntity->setOrder($salesOrderEntity);

        //Assert
        $this->assertTrue($condition->check($salesOrderItemEntity));
    }
}
