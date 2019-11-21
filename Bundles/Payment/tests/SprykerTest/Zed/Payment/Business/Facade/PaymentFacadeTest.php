<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Payment\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\StoreRelationBuilder;
use Generated\Shared\Transfer\PaymentMethodTransfer;
use Generated\Shared\Transfer\StoreRelationTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodQuery;
use Orm\Zed\Payment\Persistence\SpyPaymentMethodStoreQuery;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Payment
 * @group Business
 * @group Facade
 * @group Facade
 * @group PaymentFacadeTest
 * Add your own group annotations below this line
 */
class PaymentFacadeTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Payment\PaymentBusinessTester
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\Payment\Business\PaymentFacadeInterface
     */
    protected $paymentFacade;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->paymentFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testFindPaymentMethodByIdShouldFindPaymentMethod(): void
    {
        // Arrange
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);

        // Act
        $paymentMethodResponseTransfer = $this->paymentFacade->findPaymentMethodById($paymentMethodTransfer->getIdPaymentMethod());

        // Assert
        $this->assertTrue($paymentMethodResponseTransfer->getIsSuccessful(), 'Payment method should be found');
        $this->assertNotNull($paymentMethodResponseTransfer->getPaymentMethod(), 'Payment method should not be empty');
    }

    /**
     * @return void
     */
    public function testFindPaymentMethodByIdShouldNotFindPaymentMethod(): void
    {
        // Arrange
        $this->tester->ensurePaymentMethodTableIsEmpty();

        // Act
        $paymentMethodResponseTransfer = $this->paymentFacade->findPaymentMethodById(1);

        // Assert
        $this->assertFalse($paymentMethodResponseTransfer->getIsSuccessful(), 'Payment method should not be found');
    }

    /**
     * @return void
     */
    public function testUpdateMethodShouldUpdateShipmentMethodWithStoreRelation(): void
    {
        // Arrange
        $this->tester->ensurePaymentMethodTableIsEmpty();
        $paymentProviderTransfer = $this->tester->havePaymentProvider();
        $paymentMethodTransfer = $this->tester->havePaymentMethod([
            PaymentMethodTransfer::METHOD_NAME => 'test',
            PaymentMethodTransfer::ID_PAYMENT_PROVIDER => $paymentProviderTransfer->getIdPaymentProvider(),
        ]);
        $storeTransfer = $this->tester->haveStore([
            StoreTransfer::NAME => 'DE',
        ]);
        $storeRelationTransfer = (new StoreRelationBuilder())->seed([
            StoreRelationTransfer::ID_ENTITY => $paymentMethodTransfer->getIdPaymentMethod(),
            StoreRelationTransfer::ID_STORES => [
                $storeTransfer->getIdStore(),
            ],
            StoreRelationTransfer::STORES => [
                $storeTransfer,
            ],
        ])->build();
        $paymentMethodTransfer->setStoreRelation($storeRelationTransfer);
        $paymentMethodTransfer->setMethodName('test1');

        // Act
        $this->paymentFacade->updatePaymentMethod($paymentMethodTransfer);

        // Assert
        $resultPaymentMethodEntity = SpyPaymentMethodQuery::create()
            ->filterByIdPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
            ->findOne();
        $storeRelationExist = SpyPaymentMethodStoreQuery::create()
            ->filterByFkPaymentMethod($paymentMethodTransfer->getIdPaymentMethod())
            ->exists();
        $this->assertEquals('test1', $resultPaymentMethodEntity->getName(), 'Payment method name should match to the expected value');
        $this->assertTrue($storeRelationExist, 'Payment method store relation should exists');
    }
}
