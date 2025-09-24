<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerTest\Zed\Customer\Business\CustomerExpander;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\AddressBuilder;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpander;
use Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface;
use SprykerTest\Zed\Customer\CustomerBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group CustomerExpander
 * @group CustomerExpanderTest
 * Add your own group annotations below this line
 */
class CustomerExpanderTest extends Unit
{
    /**
     * @var \SprykerTest\Zed\Customer\CustomerBusinessTester
     */
    protected CustomerBusinessTester $tester;

    /**
     * @return void
     */
    public function testExpandWithNoPluginsReturnsOriginalTransfer(): void
    {
        // Arrange
        $customerExpander = new CustomerExpander([]);
        $customerTransfer = $this->tester->createTestCustomerTransfer();

        // Act
        $resultCustomerTransfer = $customerExpander->expand($customerTransfer);

        // Assert
        $this->assertSame($customerTransfer, $resultCustomerTransfer);
        $this->assertEquals(CustomerBusinessTester::TESTER_EMAIL, $resultCustomerTransfer->getEmail());
    }

    /**
     * @return void
     */
    public function testExpandCustomerTransferWithCustomerAddress(): void
    {
        // Arrange
        $customerTransfer = $this->tester->createTestCustomerTransfer();
        $plugin = $this->createMockCustomerBillingAddressExpanderPlugin();
        $customerExpander = new CustomerExpander([$plugin]);

        // Act
        $resultCustomerTransfer = $customerExpander->expand($customerTransfer);

        // Assert
        $this->assertCount(1, $resultCustomerTransfer->getBillingAddress());
        $billingAddress = $resultCustomerTransfer->getBillingAddress()->offsetGet(0);
        $this->assertEquals('123 Main St', $billingAddress->getAddress1());
        $this->assertEquals('Anytown', $billingAddress->getCity());
        $this->assertEquals('12345', $billingAddress->getZipCode());
        $this->assertEquals('US', $billingAddress->getIso2Code());
        $this->assertTrue($billingAddress->getIsDefaultBilling());
        $this->assertEquals(CustomerBusinessTester::TESTER_EMAIL, $resultCustomerTransfer->getEmail());
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Plugin\CustomerTransferExpanderPluginInterface
     */
    protected function createMockCustomerBillingAddressExpanderPlugin(): CustomerTransferExpanderPluginInterface
    {
        $mockPlugin = $this->getMockBuilder(CustomerTransferExpanderPluginInterface::class)
            ->getMock();

        $mockPlugin->method('expandTransfer')
            ->willReturnCallback(function (CustomerTransfer $customerTransfer) {
                $customerAddressTransfer = (new AddressBuilder([
                    'city' => 'Anytown',
                    'address1' => '123 Main St',
                    'zipCode' => '12345',
                    'iso2Code' => 'US',
                    'isDefaultBilling' => true,
                ]))->build();

                $customerTransfer->setBillingAddress((new ArrayObject([$customerAddressTransfer])));

                return $customerTransfer;
            });

        return $mockPlugin;
    }
}
