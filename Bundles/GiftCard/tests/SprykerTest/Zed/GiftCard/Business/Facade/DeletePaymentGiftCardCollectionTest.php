<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\GiftCard\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\PaymentGiftCardCollectionDeleteCriteriaTransfer;
use SprykerTest\Zed\GiftCard\GiftCardBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group GiftCard
 * @group Business
 * @group Facade
 * @group DeletePaymentGiftCardCollectionTest
 * Add your own group annotations below this line
 */
class DeletePaymentGiftCardCollectionTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\GiftCard\GiftCardBusinessTester
     */
    protected GiftCardBusinessTester $tester;

    /**
     * @return void
     */
    public function testDeletesFoundBySalesPaymentIdsGiftCardPayments(): void
    {
        // Arrange
        $idSalesPayment1 = $this->tester->createSalesPaymentEntity();
        $idSalesPayment2 = $this->tester->createSalesPaymentEntity();
        $this->tester->createPaymentGiftCardEntity($idSalesPayment1);
        $this->tester->createPaymentGiftCardEntity($idSalesPayment2);

        // Act
        $this->tester->getFacade()->deletePaymentGiftCardCollection(
            (new PaymentGiftCardCollectionDeleteCriteriaTransfer())->addIdSalesPayment($idSalesPayment1),
        );

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment1, 0);
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment2, 1);
    }

    /**
     * @return void
     */
    public function testDoesNothingWhenGiftCardPaymentsAreNotFoundBySalesPaymentIds(): void
    {
        // Arrange
        $idSalesPayment1 = $this->tester->createSalesPaymentEntity();
        $idSalesPayment2 = $this->tester->createSalesPaymentEntity();
        $this->tester->createPaymentGiftCardEntity($idSalesPayment1);

        // Act
        $this->tester->getFacade()->deletePaymentGiftCardCollection(
            (new PaymentGiftCardCollectionDeleteCriteriaTransfer())->addIdSalesPayment($idSalesPayment2),
        );

        // Assert
        $this->tester->assertPaymentGiftCardExistBySalesPaymentId($idSalesPayment1, 1);
    }
}
