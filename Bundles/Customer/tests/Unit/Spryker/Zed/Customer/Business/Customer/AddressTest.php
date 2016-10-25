<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Unit\Spryker\Zed\Customer\Business\Customer;

use Codeception\TestCase\Test;
use Generated\Shared\Transfer\AddressTransfer;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainer;

/**
 * @group Unit
 * @group Spryker
 * @group Zed
 * @group Customer
 * @group Business
 * @group Customer
 * @group AddressTest
 */
class AddressTest extends Test
{

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\Address
     */
    protected $address;

    /**
     * @return void
     */
    public function setUp()
    {
        $queryContainer = new CustomerQueryContainer();
        $countryFacade = $this->createCountryFacadeMock();
        $localeFacade = $this->createLocaleFacadeMock();
        $this->address = new Address($queryContainer, $countryFacade, $localeFacade);
    }

    /**
     * @return void
     */
    public function testDeleteAddressException()
    {
        $addressTransfer = new AddressTransfer();

        $this->expectException(CustomerNotFoundException::class);
        $this->expectExceptionMessage('Customer not found for email `` or ID ``.');

        $this->address->deleteAddress($addressTransfer);
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createCountryFacadeMock()
    {
        return $this->getMockBuilder(CustomerToCountryInterface::class)->getMock();
    }

    /**
     * @return \Spryker\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function createLocaleFacadeMock()
    {
        return $this->getMockBuilder(CustomerToLocaleInterface::class)->getMock();
    }

}
