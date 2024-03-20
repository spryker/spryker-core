<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer;

use Codeception\Actor;
use Codeception\Stub;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\Communication\CustomerCommunicationFactory;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;

/**
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = null)
 *
 * @SuppressWarnings(\SprykerTest\Zed\Customer\PHPMD)
 */
class CustomerCommunicationTester extends Actor
{
    use _generated\CustomerCommunicationTesterActions;

    /**
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|(\object&\PHPUnit\Framework\MockObject\MockObject&\Spryker\Zed\Customer\Business\CustomerFacade)|(\object&\PHPUnit\Framework\MockObject\MockObject&\Spryker\Zed\Customer\Business\CustomerFacade&\object&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function createCustomerFacadeMock(): CustomerFacade
    {
        return Stub::makeEmpty(CustomerFacade::class);
    }

    /**
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Communication\CustomerCommunicationFactory&\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Communication\CustomerCommunicationFactory&\object&\PHPUnit\Framework\MockObject\MockObject&\object&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function createCustomerCommunicationFactoryMock(): CustomerCommunicationFactory
    {
        return Stub::makeEmpty(CustomerCommunicationFactory::class);
    }

    /**
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface&\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Dependency\Facade\CustomerToStoreFacadeInterface&\object&\PHPUnit\Framework\MockObject\MockObject&\object&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function createStoreFacadeMock(): CustomerToStoreFacadeInterface
    {
        return Stub::makeEmpty(CustomerToStoreFacadeInterface::class);
    }

    /**
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface&\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Dependency\Facade\CustomerToCountryInterface&\object&\PHPUnit\Framework\MockObject\MockObject&\object&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function createCountryFacadeMock(): CustomerToCountryInterface
    {
        return Stub::makeEmpty(CustomerToCountryInterface::class);
    }

    /**
     * @return (\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface&\object&\PHPUnit\Framework\MockObject\MockObject)|(\Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface&\object&\PHPUnit\Framework\MockObject\MockObject&\object&\PHPUnit\Framework\MockObject\MockObject)
     */
    public function createCustomerQueryContainerMock(): CustomerQueryContainerInterface
    {
        return Stub::makeEmpty(CustomerQueryContainerInterface::class);
    }
}
