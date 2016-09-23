<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Shared\Customer\Code\Messages;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use Spryker\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Library\Generator\StringGenerator;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class Customer
{

    const BCRYPT_FACTOR = 12;
    const BCRYPT_SALT = '';

    /**
     * @var \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var \Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface
     */
    protected $customerReferenceGenerator;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface[]
     */
    protected $passwordRestoredConfirmationSender = [];

    /**
     * @var \Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface[]
     */
    protected $passwordRestoreTokenSender = [];

    /**
     * @var \Spryker\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface[]
     */
    protected $registrationTokenSender = [];

    /**
     * @var \Spryker\Zed\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface $customerReferenceGenerator
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     */
    public function __construct(CustomerQueryContainerInterface $queryContainer, CustomerReferenceGeneratorInterface $customerReferenceGenerator, CustomerConfig $customerConfig)
    {
        $this->queryContainer = $queryContainer;
        $this->customerReferenceGenerator = $customerReferenceGenerator;
        $this->customerConfig = $customerConfig;
    }

    /**
     * @param string $email
     *
     * @return bool
     */
    public function hasEmail($email)
    {
        $customerCount = $this->queryContainer
            ->queryCustomerByEmail($email)
            ->count();

        return ($customerCount > 0);
    }

    /**
     * @param \Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface $sender
     *
     * @return void
     */
    public function addPasswordRestoredConfirmationSender(PasswordRestoredConfirmationSenderPluginInterface $sender)
    {
        $this->passwordRestoredConfirmationSender[] = $sender;
    }

    /**
     * @param \Spryker\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface $sender
     *
     * @return void
     */
    public function addPasswordRestoreTokenSender(PasswordRestoreTokenSenderPluginInterface $sender)
    {
        $this->passwordRestoreTokenSender[] = $sender;
    }

    /**
     * @param \Spryker\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface $sender
     *
     * @return void
     */
    public function addRegistrationTokenSender(RegistrationTokenSenderPluginInterface $sender)
    {
        $this->registrationTokenSender[] = $sender;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);
        $customerTransfer->fromArray($customerEntity->toArray(), true);
        $addresses = $customerEntity->getAddresses();
        if ($addresses) {
            $customerTransfer->setAddresses($this->entityCollectionToTransferCollection($addresses, $customerEntity));
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function register(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customerEntity = new SpyCustomer();
        $customerEntity->fromArray($customerTransfer->toArray());

        if (!$this->isEmailAvailableForCustomer($customerEntity)) {
            $customerResponseTransfer = $this->createCustomerEmailAlreadyUsedResponse();

            return $customerResponseTransfer;
        }

        $customerResponseTransfer = $this->createCustomerResponseTransfer();

        $customerEntity->setCustomerReference($this->customerReferenceGenerator->generateCustomerReference($customerTransfer));
        $customerEntity->setRegistrationKey($this->generateKey());

        $customerEntity->save();

        $customerTransfer->setIdCustomer($customerEntity->getPrimaryKey());
        $customerTransfer->setCustomerReference($customerEntity->getCustomerReference());
        $customerTransfer->setRegistrationKey($customerEntity->getRegistrationKey());

        $this->sendRegistrationToken($customerTransfer);

        $customerResponseTransfer
            ->setIsSuccess(true)
            ->setCustomerTransfer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @return string
     */
    protected function generateKey()
    {
        $generator = new StringGenerator();

        return $generator->generateRandomString();
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function sendPasswordRestoreToken(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->get($customerTransfer);
        $confirmationLink = $this->customerConfig
            ->getCustomerPasswordRestoreTokenUrl($customerTransfer->getRestorePasswordKey());
        foreach ($this->passwordRestoreTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $confirmationLink);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function sendRegistrationToken(CustomerTransfer $customerTransfer)
    {
        if (!$customerTransfer->getSendPasswordToken()) {
            return false;
        }
        $confirmationLink = $this->customerConfig
            ->getRegisterConfirmTokenUrl($customerTransfer->getRegistrationKey());
        foreach ($this->registrationTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $confirmationLink);
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function sendPasswordRestoreConfirmation(CustomerTransfer $customerTransfer)
    {
        foreach ($this->passwordRestoredConfirmationSender as $sender) {
            $sender->send($customerTransfer->getEmail());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @throws \Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        $customerEntity = $this->queryContainer->queryCustomerByRegistrationKey($customerTransfer->getRegistrationKey())
            ->findOne();
        if ($customerEntity === null) {
            throw new CustomerNotFoundException(sprintf('Customer for registration key `%s` not found', $customerTransfer->getRegistrationKey()));
        }

        $customerEntity->setRegistered(new \DateTime());
        $customerEntity->setRegistrationKey(null);

        $customerEntity->save();
        $customerTransfer->fromArray($customerEntity->toArray(), true);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function sendPasswordRestoreMail(CustomerTransfer $customerTransfer)
    {
        $customerResponseTransfer = $this->createCustomerResponseTransfer();

        try {
            $customerEntity = $this->getCustomer($customerTransfer);
        } catch (CustomerNotFoundException $e) {
            return $customerResponseTransfer;
        }

        $customerEntity->setRestorePasswordDate(new \DateTime());
        $customerEntity->setRestorePasswordKey($this->generateKey());

        $customerEntity->save();

        $customerTransfer->fromArray($customerEntity->toArray(), true);
        $this->sendPasswordRestoreToken($customerTransfer);

        $customerResponseTransfer->setCustomerTransfer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customerResponseTransfer = $this->createCustomerResponseTransfer();

        try {
            $customerEntity = $this->getCustomer($customerTransfer);
        } catch (CustomerNotFoundException $e) {
            $customerError = new CustomerErrorTransfer();
            $customerError->setMessage(Messages::CUSTOMER_TOKEN_INVALID);

            $customerResponseTransfer
                ->setIsSuccess(false)
                ->addError($customerError);

            return $customerResponseTransfer;
        }

        $customerEntity->setRestorePasswordDate(null);
        $customerEntity->setRestorePasswordKey(null);

        $customerEntity->setPassword($customerTransfer->getPassword());

        $customerEntity->save();
        $customerTransfer->fromArray($customerEntity->toArray(), true);
        $this->sendPasswordRestoreConfirmation($customerTransfer);

        $customerResponseTransfer->setCustomerTransfer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function delete(CustomerTransfer $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);
        $customerEntity->delete();

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function update(CustomerTransfer $customerTransfer)
    {
        if (!empty($customerTransfer->getNewPassword())) {
            $customerResponseTransfer = $this->updatePassword(clone $customerTransfer);
            if ($customerResponseTransfer->getIsSuccess() === false) {
                return $customerResponseTransfer;
            }
        }

        $customerEntity = $this->getCustomer($customerTransfer);
        $customerEntity->fromArray($customerTransfer->modifiedToArray());

        if (!$this->isEmailAvailableForCustomer($customerEntity)) {
            return $this->createCustomerEmailAlreadyUsedResponse();
        }

        $customerResponseTransfer = $this->createCustomerResponseTransfer();

        $changedRows = $customerEntity->save();

        $customerResponseTransfer
            ->setIsSuccess($changedRows > 0)
            ->setCustomerTransfer($customerTransfer);

        if ($customerTransfer->getSendPasswordToken()) {
            $this->sendPasswordRestoreMail($customerTransfer);
        }

        return $customerResponseTransfer;
    }

    /**
     * @param bool $isSuccess
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function createCustomerResponseTransfer($isSuccess = true)
    {
        $customerResponseTransfer = new CustomerResponseTransfer();
        $customerResponseTransfer->setIsSuccess($isSuccess);

        return $customerResponseTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function createCustomerEmailAlreadyUsedResponse()
    {
        $customerErrorTransfer = new CustomerErrorTransfer();
        $customerErrorTransfer->setMessage(Messages::CUSTOMER_EMAIL_ALREADY_USED);

        $customerResponseTransfer = $this->createCustomerResponseTransfer(false);
        $customerResponseTransfer->addError($customerErrorTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return bool
     */
    protected function isEmailAvailableForCustomer(SpyCustomer $customerEntity)
    {
        $count = $this->queryContainer
            ->queryCustomerByEmailApartFromIdCustomer($customerEntity->getEmail(), $customerEntity->getIdCustomer())
            ->count();

        return ($count === 0);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function updatePassword(CustomerTransfer $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);

        $customerResponseTransfer = $this->getCustomerPasswordInvalidResponse($customerEntity, $customerTransfer);
        if (!$customerResponseTransfer->getIsSuccess()) {
            return $customerResponseTransfer;
        }

        $customerTransfer = $this->encryptNewPassword($customerTransfer);

        $customerEntity->setPassword($customerTransfer->getNewPassword());

        $changedRows = $customerEntity->save();

        $customerTransfer->fromArray($customerEntity->toArray(), true);

        $customerResponseTransfer
            ->setIsSuccess($changedRows > 0)
            ->setCustomerTransfer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function getCustomerPasswordInvalidResponse(SpyCustomer $customerEntity, CustomerTransfer $customerTransfer)
    {
        $customerResponseTransfer = new CustomerResponseTransfer();
        $customerResponseTransfer->setIsSuccess(true);

        if (!$this->isValidPassword($customerEntity->getPassword(), $customerTransfer->getPassword())) {
            $customerErrorTransfer = new CustomerErrorTransfer();
            $customerErrorTransfer
                ->setMessage(Messages::CUSTOMER_PASSWORD_INVALID);
            $customerResponseTransfer
                ->setIsSuccess(false)
                ->addError($customerErrorTransfer);
        }

        return $customerResponseTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomerAddress $addressEntity
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    protected function entityToTransfer(SpyCustomerAddress $addressEntity)
    {
        $addressTransfer = new AddressTransfer();
        $addressTransfer->fromArray($addressEntity->toArray(), true);
        $addressTransfer->setIso2Code($addressEntity->getCountry()->getIso2Code());

        return $addressTransfer;
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $entities
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function entityCollectionToTransferCollection(ObjectCollection $entities, SpyCustomer $customer)
    {
        $addressCollection = new AddressesTransfer();
        foreach ($entities->getData() as $address) {
            $addressTransfer = $this->entityToTransfer($address);

            if ($customer->getDefaultBillingAddress() === $address->getIdCustomerAddress()) {
                $addressTransfer->setIsDefaultBilling(true);
            }
            if ($customer->getDefaultShippingAddress() === $address->getIdCustomerAddress()) {
                $addressTransfer->setIsDefaultShipping(true);
            }

            $addressCollection->addAddress($addressTransfer);
        }

        return $addressCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @throws \Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomer
     */
    protected function getCustomer(CustomerTransfer $customerTransfer)
    {
        $customerEntity = null;

        if ($customerTransfer->getIdCustomer()) {
            $customerEntity = $this->queryContainer->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne();
        } elseif ($customerTransfer->getEmail()) {
            $customerEntity = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne();
        } elseif ($customerTransfer->getRestorePasswordKey()) {
            $customerEntity = $this->queryContainer->queryCustomerByRestorePasswordKey($customerTransfer->getRestorePasswordKey())
                ->findOne();
        }

        if ($customerEntity !== null) {
            return $customerEntity;
        }

        throw new CustomerNotFoundException(sprintf(
            'Customer not found by either ID `%s`, email `%s` or restore password key `%s`.',
            $customerTransfer->getIdCustomer(),
            $customerTransfer->getEmail(),
            $customerTransfer->getRestorePasswordKey()
        ));
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function hasCustomer(CustomerTransfer $customerTransfer)
    {
        $result = false;
        $customerEntity = null;

        if ($customerTransfer->getIdCustomer()) {
            $customerEntity = $this->queryContainer
                ->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne();
        } elseif ($customerTransfer->getEmail()) {
            $customerEntity = $this->queryContainer
                ->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne();
        }

        if ($customerEntity !== null) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    public function tryAuthorizeCustomerByEmailAndPassword(CustomerTransfer $customerTransfer)
    {
        $result = false;

        $customerEntity = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
            ->findOne();

        if ($customerEntity !== null) {
            $result = $this->isValidPassword($customerEntity->getPassword(), $customerTransfer->getPassword());
        }

        return $result;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function encryptPassword(CustomerTransfer $customerTransfer)
    {
        $currentPassword = $customerTransfer->getPassword();
        $customerTransfer->setPassword($this->getEncodedPassword($currentPassword));

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function encryptNewPassword(CustomerTransfer $customerTransfer)
    {
        $currentPassword = $customerTransfer->getNewPassword();
        $customerTransfer->setNewPassword($this->getEncodedPassword($currentPassword));

        return $customerTransfer;
    }

    /**
     * @param string $currentPassword
     *
     * @return string
     */
    protected function getEncodedPassword($currentPassword)
    {
        $newPassword = $currentPassword;

        if (mb_substr($currentPassword, 0, 2) !== '$2') {
            $encoder = new BCryptPasswordEncoder(self::BCRYPT_FACTOR);

            $newPassword = $encoder->encodePassword($currentPassword, self::BCRYPT_SALT);
        }

        return $newPassword;
    }

    /**
     * @param string $hash
     * @param string $rawPassword
     *
     * @return bool
     */
    protected function isValidPassword($hash, $rawPassword)
    {
        $encoder = new BCryptPasswordEncoder(self::BCRYPT_FACTOR);

        return $encoder->isPasswordValid($hash, $rawPassword, self::BCRYPT_SALT);
    }

}
