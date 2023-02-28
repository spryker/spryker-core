<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\AvailabilityNotification\Business;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AvailabilityNotificationCriteriaTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\StoreTransfer;
use Spryker\Zed\AvailabilityNotification\AvailabilityNotificationDependencyProvider;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToMailFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToProductFacadeInterface;
use Spryker\Zed\AvailabilityNotification\Dependency\Facade\AvailabilityNotificationToStoreFacadeInterface;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group AvailabilityNotification
 * @group Business
 * @group Facade
 * @group AvailabilityNotificationFacadeTest
 * Add your own group annotations below this line
 */
class AvailabilityNotificationFacadeTest extends Unit
{
    /**
     * @var string
     */
    protected const TESTER_INVALID_EMAIL = 'invalid<>example@spryker.com';

    /**
     * @var string
     */
    protected const TESTER_INCORRECT_SUBSCRIPTION_KEY = '992233445566778899';

    /**
     * @var string
     */
    protected const STORE_DE = 'DE';

    /**
     * @var string
     */
    protected const STORE_AT = 'AT';

    /**
     * @var \SprykerTest\Zed\AvailabilityNotification\AvailabilityNotificationBusinessTester|\SprykerTest\Zed\AvailabilityNotification\Helper\AvailabilityNotificationDataHelper|\SprykerTest\Shared\Product\Helper\ProductDataHelper|\SprykerTest\Shared\Customer\Helper\CustomerDataHelper
     */
    protected $tester;

    /**
     * @var \Spryker\Zed\AvailabilityNotification\Business\AvailabilityNotificationFacade
     */
    protected $availabilityNotificationFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->availabilityNotificationFacade = $this->tester->getFacade();
    }

    /**
     * @return void
     */
    public function testGuestSubscribeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testCustomerSubscribeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $this->tester->haveCustomer(),
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeFailsWhenEmailIsInvalid(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            null,
            [
                'email' => static::TESTER_INVALID_EMAIL,
            ],
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testSubscribeForAlreadySubscribedTypeShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeBySubscriptionKeyShouldSucceed(): void
    {
        $this->mockMailDependency();

        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscription(
            $this->tester->haveProduct(),
        );

        $response = $this->availabilityNotificationFacade->unsubscribeBySubscriptionKey($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeByCustomerReferenceAndSkuShouldSucceed(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $this->tester->haveCustomer(),
        );

        $this->mockMailDependency();

        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $response = $this->availabilityNotificationFacade->unsubscribeByCustomerReferenceAndSku($availabilityNotificationSubscription);

        $this->assertTrue($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testUnsubscribeWithIncorrectSubscriptionKeyShouldFail(): void
    {
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            null,
            [
                'subscription_key' => static::TESTER_INCORRECT_SUBSCRIPTION_KEY,
            ],
        );

        $this->mockMailDependency();

        $response = $this->availabilityNotificationFacade->unsubscribeBySubscriptionKey($availabilityNotificationSubscription);

        $this->assertFalse($response->getIsSuccess());
    }

    /**
     * @return void
     */
    public function testAnonymize(): void
    {
        $customer = $this->tester->haveCustomer();
        $availabilityNotificationSubscription = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $this->tester->haveProduct(),
            $customer,
        );

        $this->mockMailDependency();

        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscription);

        $this->availabilityNotificationFacade->anonymizeSubscription($customer);

        $result = $this->tester
            ->findAvailabilityNotificationByCustomerReferenceAndSku(
                $customer->getCustomerReference(),
                $availabilityNotificationSubscription->getSku(),
                $availabilityNotificationSubscription->getStore()->getIdStore(),
            );

        $this->assertNull($result);
    }

    /**
     * @return void
     */
    public function testExpandCustomerTransferWithAvailabilityNotificationSubscriptionList(): void
    {
        $product1 = $this->tester->haveProduct();
        $product2 = $this->tester->haveProduct();
        $customer = $this->tester->haveCustomer();
        $this->mockMailDependency();
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product1,
                $customer,
            ),
        );
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product2,
                $customer,
            ),
        );
        $expandedCustomerTransfer = $this->availabilityNotificationFacade->expandCustomerTransferWithAvailabilityNotificationSubscriptionList($customer);
        $this->assertEmpty(
            array_diff(
                [
                    $product1->getSku(),
                    $product2->getSku(),
                ],
                $expandedCustomerTransfer->getAvailabilityNotificationSubscriptionSkus(),
            ),
        );
    }

    /**
     * @return void
     */
    public function testGetAvailabilityNotifications(): void
    {
        $product1 = $this->tester->haveProduct();
        $product2 = $this->tester->haveProduct();
        $customer = $this->tester->haveCustomer();
        $this->mockMailDependency();
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product1,
                $customer,
            ),
        );
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $product2,
                $customer,
            ),
        );
        $availabilityNotificationSubscriptionCollectionTransfer = $this->availabilityNotificationFacade->getAvailabilityNotifications(
            (new AvailabilityNotificationCriteriaTransfer())->addCustomerReference($customer->getCustomerReference()),
        );
        $this->assertEquals(2, $availabilityNotificationSubscriptionCollectionTransfer->getAvailabilityNotificationSubscriptions()->count());
    }

    /**
     * @return void
     */
    public function testGetAvailabilityNotificationsShouldFilterNotificationSubscriptionsByStoreName(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer();
        $productConcreteTransfer = $this->tester->haveProduct();
        $productConcreteTransfer2 = $this->tester->haveProduct();

        $storeTransferDE = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_DE,
        ]);
        $storeTransferAT = $this->tester->haveStore([
            StoreTransfer::NAME => static::STORE_AT,
        ]);

        $this->mockMailDependency();

        $this->mockStoreFacadeDependency($storeTransferDE);
        $this->mockProductFacadeDependency($productConcreteTransfer);
        $availabilityNotificationSubscriptionTransfer = $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
            $productConcreteTransfer,
            $customerTransfer,
        );
        $this->availabilityNotificationFacade->subscribe($availabilityNotificationSubscriptionTransfer);

        $this->mockStoreFacadeDependency($storeTransferAT);
        $this->mockProductFacadeDependency($productConcreteTransfer2);
        $this->availabilityNotificationFacade->subscribe(
            $this->tester->haveAvailabilityNotificationSubscriptionTransfer(
                $productConcreteTransfer2,
                $customerTransfer,
            ),
        );

        $availabilityNotificationCriteriaTransfer = (new AvailabilityNotificationCriteriaTransfer())
            ->addCustomerReference($customerTransfer->getCustomerReference())
            ->addStoreName(static::STORE_DE);

        // Act
        $availabilityNotificationSubscriptions = $this->availabilityNotificationFacade
            ->getAvailabilityNotifications($availabilityNotificationCriteriaTransfer)
            ->getAvailabilityNotificationSubscriptions();

        // Assert
        $this->assertCount(1, $availabilityNotificationSubscriptions);
        $this->assertSame(
            $availabilityNotificationSubscriptionTransfer->getSubscriptionKey(),
            $availabilityNotificationSubscriptions->getIterator()->current()->getSubscriptionKey(),
        );
    }

    /**
     * @return void
     */
    protected function mockMailDependency(): void
    {
        $this->tester
            ->setDependency(
                AvailabilityNotificationDependencyProvider::FACADE_MAIL,
                $this->createMock(AvailabilityNotificationToMailFacadeInterface::class),
            );
    }

    /**
     * @param \Generated\Shared\Transfer\StoreTransfer $storeTransfer
     *
     * @return void
     */
    protected function mockStoreFacadeDependency(StoreTransfer $storeTransfer): void
    {
        $storeFacadeMock = $this->createMock(AvailabilityNotificationToStoreFacadeInterface::class);
        $storeFacadeMock->method('getCurrentStore')->willReturn($storeTransfer);

        $this->tester->setDependency(
            AvailabilityNotificationDependencyProvider::FACADE_STORE,
            $storeFacadeMock,
        );
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return void
     */
    protected function mockProductFacadeDependency(ProductConcreteTransfer $productConcreteTransfer): void
    {
        $productFacadeMock = $this->createMock(AvailabilityNotificationToProductFacadeInterface::class);
        $productFacadeMock->method('findProductAbstractById')
            ->with($productConcreteTransfer->getFkProductAbstract())
            ->willReturn(null);

        $productFacadeMock->method('getProductConcrete')
            ->willReturn($productConcreteTransfer);

        $this->tester->setDependency(
            AvailabilityNotificationDependencyProvider::FACADE_PRODUCT,
            $productFacadeMock,
        );
    }
}
