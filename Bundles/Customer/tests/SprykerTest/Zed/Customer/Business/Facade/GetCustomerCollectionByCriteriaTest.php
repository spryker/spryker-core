<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Generated\Shared\DataBuilder\CustomerBuilder;
use Generated\Shared\Transfer\CustomerCriteriaFilterTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group GetCustomerCollectionByCriteriaTest
 * Add your own group annotations below this line
 */
class GetCustomerCollectionByCriteriaTest extends AbstractCustomerFacadeTest
{
    /**
     * @dataProvider getCustomerDataProvider
     *
     * @param array $usersData
     * @param \Generated\Shared\Transfer\CustomerCriteriaFilterTransfer $criteriaFilterTransfer
     * @param int $expectedCount
     *
     * @return void
     */
    public function testGetCustomerCollectionByCriteriaShouldReturnCollectionOfCustomers(
        array $usersData,
        CustomerCriteriaFilterTransfer $criteriaFilterTransfer,
        int $expectedCount
    ): void {
        // Arrange
        foreach ($usersData as $item) {
            $this->createCustomerUsingCustomerDataProviderUserData($item);
        }

        // Assert
        $this->assertSame(
            $expectedCount,
            $this->tester->getCustomerFacade()->getCustomerCollectionByCriteria($criteriaFilterTransfer)->getCustomers()->count(),
        );
    }

    /**
     * @return array
     */
    protected function getCustomerDataProvider(): array
    {
        return [
            'get customers with empty password - expects 2' => [
                $this->getUsersData(),
                (new CustomerCriteriaFilterTransfer())->setPasswordExists(false)
                    ->setRestorePasswordKeyExists(true),
                2,
            ],
            'get customers with empty password and empty password restore key - expects 1' => [
                $this->getUsersData(),
                (new CustomerCriteriaFilterTransfer())
                    ->setPasswordExists(false)
                    ->setRestorePasswordKeyExists(false),
                1,
            ],
        ];
    }

    /**
     * @return array
     */
    protected function getUsersData(): array
    {
        $customer1 = (new CustomerBuilder())->build();
        $customer2 = (new CustomerBuilder())->build();
        $customer3 = (new CustomerBuilder())->build();

        return [
            [
                'email' => $customer1->getEmail(),
                'password' => null,
                'passwordRestoreKey' => null,
                'customerReference' => '89712978124789',
            ],
            [
                'email' => $customer2->getEmail(),
                'password' => null,
                'passwordRestoreKey' => 'fee0292350a14da40ac6f8f9d6cd26ad',
                'customerReference' => '12478124891512',
            ],
            [
                'email' => $customer3->getEmail(),
                'password' => static::VALUE_VALID_PASSWORD,
                'passwordRestoreKey' => 'fee0292350a14da40ac6f8f9d6cd26ad',
                'customerReference' => 'y1247891249871',
            ],
        ];
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return void
     */
    protected function createCustomerUsingCustomerDataProviderUserData(array $data): void
    {
        $customerEntity = (new SpyCustomer())
            ->setEmail($data['email'])
            ->setPassword($data['password'])
            ->setRestorePasswordKey($data['passwordRestoreKey'])
            ->setCustomerReference($data['customerReference']);

        $customerEntity->save();

        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerEntity->toArray(), true);

        $this->tester->addCleanup(function () use ($customerTransfer): void {
            $this->tester->getCustomerFacade()->deleteCustomer($customerTransfer);
        });
    }

    /**
     * @return void
     */
    public function testGetCustomerCollectionByCriteriaShouldFilterByCustomerIds(): void
    {
        // Arrange
        $customerTransfer = $this->tester->haveCustomer([CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD]);
        $this->tester->haveCustomer([CustomerTransfer::PASSWORD => static::VALUE_VALID_PASSWORD]);

        $customerCriteriaFilterTransfer = (new CustomerCriteriaFilterTransfer())
            ->addIdCustomer($customerTransfer->getIdCustomerOrFail());

        // Act
        $customerCollectionTransfer = $this->tester->getCustomerFacade()->getCustomerCollectionByCriteria(
            $customerCriteriaFilterTransfer,
        );

        // Assert
        $this->assertSame(
            1,
            $customerCollectionTransfer->getCustomers()->count(),
            'Customer collection was not filter by customer identifier.',
        );
        $this->assertSame(
            $customerTransfer->getIdCustomerOrFail(),
            $customerCollectionTransfer->getCustomers()->offsetGet(0)->getIdCustomerOrFail(),
            'Wrong customer was filtered.',
        );
    }
}
