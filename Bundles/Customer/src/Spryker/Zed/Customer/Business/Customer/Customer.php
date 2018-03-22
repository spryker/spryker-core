<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Business\Customer;

use DateTime;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\MailTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddress;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\UtilTextService;
use Spryker\Shared\Customer\Code\Messages;
use Spryker\Shared\Kernel\Store;
use Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface;
use Spryker\Zed\Customer\Business\Exception\CustomerNotFoundException;
use Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface;
use Spryker\Zed\Customer\Communication\Plugin\Mail\CustomerRegistrationMailTypePlugin;
use Spryker\Zed\Customer\Communication\Plugin\Mail\CustomerRestoredPasswordConfirmationMailTypePlugin;
use Spryker\Zed\Customer\Communication\Plugin\Mail\CustomerRestorePasswordMailTypePlugin;
use Spryker\Zed\Customer\CustomerConfig;
use Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface;
use Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class Customer implements CustomerInterface
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
     * @var \Spryker\Zed\Customer\CustomerConfig
     */
    protected $customerConfig;

    /**
     * @var \Spryker\Zed\Customer\Business\Customer\EmailValidatorInterface
     */
    protected $emailValidator;

    /**
     * @var \Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface
     */
    protected $mailFacade;

    /**
     * @var \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface
     */
    protected $localeQueryContainer;

    /**
     * @var \Spryker\Shared\Kernel\Store
     */
    protected $store;

    /**
     * @var \Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface
     */
    protected $customerExpander;

    /**
     * @param \Spryker\Zed\Customer\Persistence\CustomerQueryContainerInterface $queryContainer
     * @param \Spryker\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface $customerReferenceGenerator
     * @param \Spryker\Zed\Customer\CustomerConfig $customerConfig
     * @param \Spryker\Zed\Customer\Business\Customer\EmailValidatorInterface $emailValidator
     * @param \Spryker\Zed\Customer\Dependency\Facade\CustomerToMailInterface $mailFacade
     * @param \Spryker\Zed\Locale\Persistence\LocaleQueryContainerInterface $localeQueryContainer
     * @param \Spryker\Shared\Kernel\Store $store
     * @param \Spryker\Zed\Customer\Business\CustomerExpander\CustomerExpanderInterface $customerExpander
     */
    public function __construct(
        CustomerQueryContainerInterface $queryContainer,
        CustomerReferenceGeneratorInterface $customerReferenceGenerator,
        CustomerConfig $customerConfig,
        EmailValidatorInterface $emailValidator,
        CustomerToMailInterface $mailFacade,
        LocaleQueryContainerInterface $localeQueryContainer,
        Store $store,
        CustomerExpanderInterface $customerExpander
    ) {
        $this->queryContainer = $queryContainer;
        $this->customerReferenceGenerator = $customerReferenceGenerator;
        $this->customerConfig = $customerConfig;
        $this->emailValidator = $emailValidator;
        $this->mailFacade = $mailFacade;
        $this->localeQueryContainer = $localeQueryContainer;
        $this->store = $store;
        $this->customerExpander = $customerExpander;
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
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);
        $customerTransfer->fromArray($customerEntity->toArray(), true);

        $customerTransfer = $this->attachAddresses($customerTransfer, $customerEntity);
        $customerTransfer = $this->attachLocale($customerTransfer, $customerEntity);
        $customerTransfer = $this->customerExpander->expand($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function attachAddresses(CustomerTransfer $customerTransfer, SpyCustomer $customerEntity)
    {
        $addresses = $customerEntity->getAddresses();
        if ($addresses) {
            $addressesTransfer = $this->entityCollectionToTransferCollection($addresses, $customerEntity);
            $customerTransfer->setAddresses($addressesTransfer);

            $customerTransfer = $this->attachAddressesTransfer($customerTransfer, $addressesTransfer);
        }

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function add($customerTransfer)
    {
        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customerEntity = new SpyCustomer();
        $customerEntity->fromArray($customerTransfer->toArray());

        if ($customerTransfer->getLocale() !== null) {
            $this->addLocaleByLocaleName($customerEntity, $customerTransfer->getLocale()->getLocaleName());
        }

        $this->addLocale($customerEntity);

        $customerResponseTransfer = $this->createCustomerResponseTransfer();
        $customerResponseTransfer = $this->validateCustomerEmail($customerResponseTransfer, $customerEntity);
        if ($customerResponseTransfer->getIsSuccess() !== true) {
            return $customerResponseTransfer;
        }

        $customerEntity->setCustomerReference($this->customerReferenceGenerator->generateCustomerReference($customerTransfer));
        $customerEntity->setRegistrationKey($this->generateKey());

        $customerEntity->save();

        $customerTransfer->setIdCustomer($customerEntity->getPrimaryKey());
        $customerTransfer->setCustomerReference($customerEntity->getCustomerReference());
        $customerTransfer->setRegistrationKey($customerEntity->getRegistrationKey());

        $customerResponseTransfer
            ->setIsSuccess(true)
            ->setCustomerTransfer($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function register(CustomerTransfer $customerTransfer)
    {
        $customerResponseTransfer = $this->add($customerTransfer);

        if (!$customerResponseTransfer->getIsSuccess()) {
            return $customerResponseTransfer;
        }

        $this->sendRegistrationToken($customerTransfer);

        if ($customerTransfer->getSendPasswordToken()) {
            $this->sendPasswordRestoreMail($customerTransfer);
        }

        return $customerResponseTransfer;
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return void
     */
    protected function addLocale(SpyCustomer $customerEntity)
    {
        if ($customerEntity->getLocale()) {
            return;
        }

        $localeName = $this->store->getCurrentLocale();
        $localeEntity = $this->localeQueryContainer->queryLocaleByName($localeName)->findOne();

        if ($localeEntity) {
            $customerEntity->setLocale($localeEntity);
        }
    }

    /**
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     * @param string $localeName
     *
     * @return void
     */
    protected function addLocaleByLocaleName(SpyCustomer $customerEntity, $localeName)
    {
        $localeEntity = $this->localeQueryContainer->queryLocaleByName($localeName)->findOne();

        if ($localeEntity) {
            $customerEntity->setLocale($localeEntity);
        }
    }

    /**
     * @return string
     */
    protected function generateKey()
    {
        $utilTextService = new UtilTextService();

        return $utilTextService->generateRandomString(32);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function sendPasswordRestoreToken(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->get($customerTransfer);
        $restorePasswordLink = $this->customerConfig
            ->getCustomerPasswordRestoreTokenUrl($customerTransfer->getRestorePasswordKey());

        $customerTransfer->setRestorePasswordLink($restorePasswordLink);

        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(CustomerRestorePasswordMailTypePlugin::MAIL_TYPE);
        $mailTransfer->setCustomer($customerTransfer);
        $mailTransfer->setLocale($customerTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return bool
     */
    protected function sendRegistrationToken(CustomerTransfer $customerTransfer)
    {
        $confirmationLink = $this->customerConfig
            ->getRegisterConfirmTokenUrl($customerTransfer->getRegistrationKey());

        $customerTransfer->setConfirmationLink($confirmationLink);

        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(CustomerRegistrationMailTypePlugin::MAIL_TYPE);
        $mailTransfer->setCustomer($customerTransfer);
        $mailTransfer->setLocale($customerTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return void
     */
    protected function sendPasswordRestoreConfirmation(CustomerTransfer $customerTransfer)
    {
        $mailTransfer = new MailTransfer();
        $mailTransfer->setType(CustomerRestoredPasswordConfirmationMailTypePlugin::MAIL_TYPE);
        $mailTransfer->setCustomer($customerTransfer);
        $mailTransfer->setLocale($customerTransfer->getLocale());

        $this->mailFacade->handleMail($mailTransfer);
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

        $customerEntity->setRegistered(new DateTime());
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

        $customerEntity->setRestorePasswordDate(new DateTime());
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

        $customerResponseTransfer = $this->createCustomerResponseTransfer();
        $customerResponseTransfer->setCustomerTransfer($customerTransfer);

        $customerEntity = $this->getCustomer($customerTransfer);
        $customerEntity->fromArray($customerTransfer->modifiedToArray());

        if ($customerTransfer->getLocale() !== null) {
            $this->addLocaleByLocaleName($customerEntity, $customerTransfer->getLocale()->getLocaleName());
        }

        $customerResponseTransfer = $this->validateCustomerEmail($customerResponseTransfer, $customerEntity);
        if (!$customerEntity->isModified() || $customerResponseTransfer->getIsSuccess() !== true) {
            return $customerResponseTransfer;
        }

        $customerEntity->save();

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
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function validateCustomerEmail(CustomerResponseTransfer $customerResponseTransfer, SpyCustomer $customerEntity)
    {
        if (!$this->emailValidator->isFormatValid($customerEntity->getEmail())) {
            $customerResponseTransfer->setIsSuccess(false);
            $customerResponseTransfer->addError(
                $this->createErrorCustomerResponseTransfer(Messages::CUSTOMER_EMAIL_FORMAT_INVALID)
            );
        }

        if (!$this->emailValidator->isEmailAvailableForCustomer($customerEntity->getEmail(), $customerEntity->getIdCustomer())) {
            $customerResponseTransfer->setIsSuccess(false);
            $customerResponseTransfer->addError(
                $this->createErrorCustomerResponseTransfer(Messages::CUSTOMER_EMAIL_ALREADY_USED)
            );
        }

        return $customerResponseTransfer;
    }

    /**
     * @param string $message
     *
     * @return \Generated\Shared\Transfer\CustomerErrorTransfer
     */
    protected function createErrorCustomerResponseTransfer($message)
    {
        $customerErrorTransfer = new CustomerErrorTransfer();
        $customerErrorTransfer->setMessage($message);

        return $customerErrorTransfer;
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
     * @param \Propel\Runtime\Collection\ObjectCollection $addressEntities
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customer
     *
     * @return \Generated\Shared\Transfer\AddressesTransfer
     */
    protected function entityCollectionToTransferCollection(ObjectCollection $addressEntities, SpyCustomer $customer)
    {
        $addressCollection = new AddressesTransfer();

        foreach ($addressEntities as $address) {
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
     * @param \Generated\Shared\Transfer\AddressesTransfer $addressesTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function attachAddressesTransfer(CustomerTransfer $customerTransfer, AddressesTransfer $addressesTransfer)
    {
        foreach ($addressesTransfer->getAddresses() as $addressTransfer) {
            if ($addressTransfer->getIsDefaultBilling()) {
                $customerTransfer->addBillingAddress($addressTransfer);
            }

            if ($addressTransfer->getIsDefaultShipping()) {
                $customerTransfer->addShippingAddress($addressTransfer);
            }
        }

        return $customerTransfer;
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

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer|null
     */
    public function findById($customerTransfer)
    {
        $customerTransfer->requireIdCustomer();

        $customerEntity = $this->queryContainer->queryCustomerById($customerTransfer->getIdCustomer())
            ->findOne();
        if ($customerEntity === null) {
            return null;
        }

        $customerTransfer = $this->hydrateCustomerTransferFromEntity($customerTransfer, $customerEntity);

        return $customerTransfer;
    }

    /**
     * @param string $customerReference
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer|null
     */
    public function findByReference($customerReference)
    {
        $customerEntity = $this->queryContainer
            ->queryCustomerByReference($customerReference)
            ->findOne();

        if ($customerEntity === null) {
            return null;
        }

        $customerTransfer = new CustomerTransfer();
        $customerTransfer = $this->hydrateCustomerTransferFromEntity($customerTransfer, $customerEntity);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function hydrateCustomerTransferFromEntity(
        CustomerTransfer $customerTransfer,
        SpyCustomer $customerEntity
    ) {
        $customerTransfer->fromArray($customerEntity->toArray(), true);
        $customerTransfer = $this->attachAddresses($customerTransfer, $customerEntity);
        $customerTransfer = $this->attachLocale($customerTransfer, $customerEntity);

        return $customerTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Orm\Zed\Customer\Persistence\SpyCustomer $customerEntity
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer
     */
    protected function attachLocale(CustomerTransfer $customerTransfer, SpyCustomer $customerEntity)
    {
        $localeEntity = $customerEntity->getLocale();
        if (!$localeEntity) {
            return $customerTransfer;
        }

        $localeTransfer = new LocaleTransfer();
        $localeTransfer->fromArray($localeEntity->toArray(), true);
        $customerTransfer->setLocale($localeTransfer);

        return $customerTransfer;
    }
}
