<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Customer;

use Codeception\Actor;
use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\PasswordHasher\Hasher\NativePasswordHasher;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\AuthenticationProviderManager;
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
     * @var int
     */
    protected const BCRYPT_FACTOR = 12;

    /**
     * @param string $hash
     * @param string $rawPassword
     * @param string $salt
     *
     * @return void
     */
    public function assertPasswordsEqual(string $hash, string $rawPassword, string $salt = ''): void
    {
        if ($this->isSymfonyVersion5() === true) {
            $this->assertPasswordIsEncoded($hash, $rawPassword, $salt);

            return;
        }

        $passwordHasher = $this->createPasswordHasher();

        $this->assertTrue($passwordHasher->verify($hash, $rawPassword), 'Passwords are not equal.');
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
     * @param string $hash
     * @param string $rawPassword
     * @param string $salt
     *
     * @return void
     */
    protected function assertPasswordIsEncoded(string $hash, string $rawPassword, string $salt = ''): void
    {
        $passwordEncoder = $this->getPasswordEncoder();

        $this->assertTrue($passwordEncoder->isPasswordValid($hash, $rawPassword, $salt), 'Passwords are not equal.');
    }

    /**
     * @return \Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface
     */
    protected function getPasswordEncoder(): PasswordEncoderInterface
    {
        return new NativePasswordEncoder(null, null, static::BCRYPT_FACTOR);
    }

    /**
     * @return \Symfony\Component\PasswordHasher\PasswordHasherInterface
     */
    protected function createPasswordHasher(): PasswordHasherInterface
    {
        return new NativePasswordHasher(null, null, static::BCRYPT_FACTOR);
    }

    /**
     * @deprecated Shim for Symfony Security Core 5.x, to be removed when Symfony Security Core dependency becomes 6.x+.
     *
     * @return bool
     */
    protected function isSymfonyVersion5(): bool
    {
        return class_exists(AuthenticationProviderManager::class);
    }
}
