<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\NativePasswordEncoder;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

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
 *
 * @method \Spryker\Zed\Customer\Business\CustomerFacadeInterface getFacade()
 */
class CustomerBusinessTester extends Actor
{
    use _generated\CustomerBusinessTesterActions;

    /**
     * @var string
     */
    public const TESTER_EMAIL = 'tester@spryker.com';

    /**
     * @var string
     */
    public const TESTER_PASSWORD = '$2tester';

    /**
     * @param string $hash
     * @param string $rawPassword
     * @param string $salt
     *
     * @return void
     */
    public function assertPasswordsEqual(string $hash, string $rawPassword, string $salt = ''): void
    {
        $passwordEncoder = $this->getPasswordEncoder();

        $this->assertTrue($passwordEncoder->isPasswordValid($hash, $rawPassword, $salt), 'Passwords are not equal.');
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createTestCustomerTransfer(): CustomerTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setEmail(static::TESTER_EMAIL);
        $customerTransfer->setPassword(static::TESTER_PASSWORD);

        return $customerTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function createTestCustomer(): CustomerTransfer
    {
        $customerTransfer = $this->createTestCustomerTransfer();
        $customerResponseTransfer = $this->getFacade()->registerCustomer($customerTransfer);
        $customerTransfer = $this->getFacade()->confirmRegistration($customerResponseTransfer->getCustomerTransfer());

        return $customerTransfer;
    }

    /**
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    protected function getPasswordEncoder(): PasswordEncoderInterface
    {
        if (class_exists(BCryptPasswordEncoder::class)) {
            return new BCryptPasswordEncoder(12);
        }

        return new NativePasswordEncoder(null, null, 12);
    }
}
