<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\SequenceNumberSettingsTransfer;
use Spryker\Shared\Kernel\Transfer\TransferInterface;
use Spryker\Zed\Customer\Business\Customer\Address;
use Spryker\Zed\Customer\Business\Customer\Customer;
use Spryker\Zed\Customer\Business\CustomerBusinessFactory;
use Spryker\Zed\Customer\Business\CustomerFacade;
use Spryker\Zed\Customer\CustomerDependencyProvider;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Customer\Dependency\Service\CustomerToUtilValidateServiceInterface;
use SprykerTest\Zed\Customer\CustomerBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Customer
 * @group Business
 * @group Facade
 * @group Facade
 * @group AbstractCustomerFacadeTest
 * Add your own group annotations below this line
 */
abstract class AbstractCustomerFacadeTest extends Unit
{
    /**
     * @var int
     */
    protected const SEQUENCE_LIMIT_CUSTOMER_PASSWORD = 3;

    /**
     * @var int
     */
    protected const MIN_LENGTH_CUSTOMER_PASSWORD = 6;

    /**
     * @var int
     */
    protected const MAX_LENGTH_CUSTOMER_PASSWORD = 12;

    /**
     * @var string
     */
    protected const TEST_INVALID_SALUTATION = 'test';

    /**
     * @uses \Orm\Zed\Customer\Persistence\Map\SpyCustomerTableMap::COL_SALUTATION_MR
     *
     * @var string
     */
    protected const TEST_SALUTATION = 'Mr';

    /**
     * @uses \Spryker\Zed\Customer\Business\CustomerPasswordPolicy::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE = 'customer.password.error.sequence';

    /**
     * @uses \Spryker\Zed\Customer\Business\Customer\Customer::GLOSSARY_KEY_MIN_LENGTH_ERROR
     *
     * @var string
     */
    protected const GLOSSARY_KEY_MIN_LENGTH_ERROR = 'customer.password.error.min_length';

    /**
     * @uses \Spryker\Zed\Customer\Business\Customer\Customer::GLOSSARY_KEY_MAX_LENGTH_ERROR
     *
     * @var string
     */
    protected const GLOSSARY_KEY_MAX_LENGTH_ERROR = 'customer.password.error.max_length';

    /**
     * @uses \Spryker\Zed\Customer\Business\DenyListCustomerPasswordPolicy::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_SEQUENCE
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_DENY_LIST = 'customer.password.error.deny_list';

    /**
     * @uses \Spryker\Zed\Customer\Business\CharacterSetCustomerPasswordPolicy::GLOSSARY_KEY_PASSWORD_POLICY_ERROR_CHARACTER_SET
     *
     * @var string
     */
    protected const GLOSSARY_KEY_PASSWORD_POLICY_ERROR_CHARACTER_SET = 'customer.password.error.character_set';

    /**
     * @var string
     */
    protected const CHARACTER_SET_REGEXP = '/^[a-zA-Z0-9]*$/';

    /**
     * @var string
     */
    protected const VALUE_CHARACTER_SET_WRONG_PASSWORD = 'cnhszer123~';

    /**
     * @var string
     */
    protected const VALUE_DENY_LIST_PASSWORD = 'qwerty';

    /**
     * @var string
     */
    protected const VALUE_NEW_PASSWORD = 'pdcEphDN';

    /**
     * @var string
     */
    protected const VALUE_LONG_PASSWORD = 'p2cfGyY4p2cfGyY4p';

    /**
     * @var string
     */
    protected const VALUE_VALID_PASSWORD = 'p2cfGyY4';

    /**
     * @var string
     */
    protected const VALUE_SEQUENCE_TOO_LONG_PASSWORD = '[3$0hhhh';

    /**
     * @var string
     */
    protected const VALUE_SHORT_PASSWORD = 'p2c';

    /**
     * @var \SprykerTest\Zed\Customer\CustomerBusinessTester
     */
    protected CustomerBusinessTester $tester;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(CustomerDependencyProvider::FACADE_MAIL, $this->getMockBuilder(CustomerToMailInterface::class)->getMock());

        $this->tester->mockConfigMethod('getCustomerReferenceDefaults', new SequenceNumberSettingsTransfer());
        $this->tester->mockConfigMethod('getCustomerPasswordMinLength', static::MIN_LENGTH_CUSTOMER_PASSWORD);
        $this->tester->mockConfigMethod('getCustomerPasswordMaxLength', static::MAX_LENGTH_CUSTOMER_PASSWORD);
    }

    /**
     * @uses UtilValidateServiceInterface::isEmailFormatValid()
     *
     * @param bool $isEmailFormatValid
     *
     * @return void
     */
    protected function mockUtilValidateService(bool $isEmailFormatValid): void
    {
        $serviceMock = $this->getMockBuilder(CustomerToUtilValidateServiceInterface::class)
            ->setMethods(['isEmailFormatValid'])
            ->getMock();

        $serviceMock
            ->expects($this->any())
            ->method('isEmailFormatValid')
            ->willReturn($isEmailFormatValid);

        $this->tester->setDependency(CustomerDependencyProvider::SERVICE_UTIL_VALIDATE, $serviceMock);
    }

    /**
     * @param string $errorMessage
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return bool
     */
    protected function hasErrorInCustomerResponseTransfer(string $errorMessage, CustomerResponseTransfer $customerResponseTransfer): bool
    {
        $errorTransfers = $customerResponseTransfer->getErrors()->getIterator();

        if (!$errorTransfers->count()) {
            return false;
        }

        return $errorTransfers->current()->getMessage() === $errorMessage;
    }

    /**
     * @param string $message
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return bool
     */
    protected function hasMessageInCustomerResponseTransfer(string $message, CustomerResponseTransfer $customerResponseTransfer): bool
    {
        $messageTransfer = $customerResponseTransfer->getMessage();
        if (!$messageTransfer) {
            return false;
        }

        return $messageTransfer->getValue() === $message;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transfer
     * @param bool $hasEmail
     *
     * @return \Spryker\Zed\Customer\Business\CustomerFacade
     */
    protected function getFacade(?TransferInterface $transfer = null, bool $hasEmail = true): CustomerFacade
    {
        $customerFacade = new CustomerFacade();
        $customerFacade->setFactory($this->getFactory($transfer, $hasEmail));

        return $customerFacade;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\TransferInterface|null $transfer
     * @param bool $hasEmail
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\CustomerBusinessFactory
     */
    protected function getFactory(?TransferInterface $transfer = null, bool $hasEmail = true): CustomerBusinessFactory
    {
        $factoryMock = $this->getMockBuilder(CustomerBusinessFactory::class)
            ->getMock();

        if ($transfer instanceof CustomerTransfer || $transfer === null) {
            $factoryMock->method('createCustomer')->willReturn($this->getCustomerMock($transfer, $hasEmail));
        }

        if ($transfer instanceof AddressTransfer) {
            $factoryMock->method('createAddress')->willReturn($this->getAddressMock($transfer));
        }

        return $factoryMock;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     * @param bool $hasEmail
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\Customer\Customer
     */
    protected function getCustomerMock(?CustomerTransfer $customerTransfer = null, bool $hasEmail = true): Customer
    {
        $customerMock = $this->getMockBuilder(Customer::class)
            ->disableOriginalConstructor()
            ->getMock();
        $customerMock->method('hasEmail')->willReturn($hasEmail);
        $customerMock->method('register')->willReturn($customerTransfer);
        $customerMock->method('confirmRegistration')->willReturn($customerTransfer);
        $customerMock->method('sendPasswordRestoreMail')->willReturn($customerTransfer);
        $customerMock->method('restorePassword')->willReturn($customerTransfer);
        $customerMock->method('get')->willReturn($customerTransfer);
        $customerMock->method('update')->willReturn($customerTransfer);
        $customerMock->method('updatePassword')->willReturn($customerTransfer);

        return $customerMock;
    }

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer|null $addressTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\Spryker\Zed\Customer\Business\Customer\Address
     */
    protected function getAddressMock(?AddressTransfer $addressTransfer = null): Address
    {
        $addressMock = $this->getMockBuilder(Address::class)
            ->disableOriginalConstructor()
            ->getMock();

        return $addressMock;
    }
}
