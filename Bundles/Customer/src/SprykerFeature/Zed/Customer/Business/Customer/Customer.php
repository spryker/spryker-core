<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\Customer;

use Generated\Shared\Customer\AddressInterface;
use Generated\Shared\Customer\CustomerInterface;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerErrorTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Shared\Customer\Code\Messages;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Customer\Business\Exception\CustomerNotFoundException;
use SprykerFeature\Zed\Customer\Business\Exception\CustomerNotUpdatedException;
use SprykerFeature\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface;
use SprykerFeature\Zed\Customer\CustomerConfig;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddress;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class Customer
{

    const BCRYPT_FACTOR = 12;
    const BCRYPT_SALT = '';

    /**
     * @var CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var CustomerReferenceGeneratorInterface
     */
    protected $customerReferenceGenerator;

    /**
     * @var PasswordRestoredConfirmationSenderPluginInterface[]
     */
    protected $passwordRestoredConfirmationSender = [];

    /**
     * @var PasswordRestoreTokenSenderPluginInterface[]
     */
    protected $passwordRestoreTokenSender = [];

    /**
     * @var RegistrationTokenSenderPluginInterface[]
     */
    protected $registrationTokenSender = [];

    /**
     * @var CustomerConfig
     */
    protected $customerConfig;

    /**
     * @param QueryContainerInterface $queryContainer
     * @param CustomerReferenceGeneratorInterface $customerReferenceGenerator
     * @param CustomerConfig $customerConfig
     */
    public function __construct(QueryContainerInterface $queryContainer, CustomerReferenceGeneratorInterface $customerReferenceGenerator, CustomerConfig $customerConfig)
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
            ->count()
        ;

        return ($customerCount > 0);
    }

    /**
     * @param PasswordRestoredConfirmationSenderPluginInterface $sender
     */
    public function addPasswordRestoredConfirmationSender(PasswordRestoredConfirmationSenderPluginInterface $sender)
    {
        $this->passwordRestoredConfirmationSender[] = $sender;
    }

    /**
     * @param PasswordRestoreTokenSenderPluginInterface $sender
     */
    public function addPasswordRestoreTokenSender(PasswordRestoreTokenSenderPluginInterface $sender)
    {
        $this->passwordRestoreTokenSender[] = $sender;
    }

    /**
     * @param RegistrationTokenSenderPluginInterface $sender
     */
    public function addRegistrationTokenSender(RegistrationTokenSenderPluginInterface $sender)
    {
        $this->registrationTokenSender[] = $sender;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    public function get(CustomerInterface $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);
        $customerTransfer->fromArray($customerEntity->toArray());
        $addresses = $customerEntity->getAddresses();
        if ($addresses) {
            $customerTransfer->setAddresses($this->entityCollectionToTransferCollection($addresses, $customerEntity));
        }

        return $customerTransfer;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws PropelException
     *
     * @return CustomerResponseTransfer
     */
    public function register(CustomerInterface $customerTransfer)
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
        return uniqid();
    }

    /**
     * @param CustomerInterface $customerTransfer
     */
    protected function sendPasswordRestoreToken(CustomerInterface $customerTransfer)
    {
        $customerTransfer = $this->get($customerTransfer);
        $confirmationLink = $this->customerConfig
            ->getCustomerPasswordRestoreTokenUrl($customerTransfer->getRestorePasswordKey())
        ;
        foreach ($this->passwordRestoreTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $confirmationLink);
        }
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return bool
     */
    protected function sendRegistrationToken(CustomerInterface $customerTransfer)
    {
        if (!$customerTransfer->getSendPasswordToken()) {
            return false;
        }
        $confirmationLink = $this->customerConfig
            ->getRegisterConfirmTokenUrl($customerTransfer->getRegistrationKey())
        ;
        foreach ($this->registrationTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $confirmationLink);
        }

        return true;
    }

    /**
     * @param CustomerInterface $customerTransfer
     */
    protected function sendPasswordRestoreConfirmation(CustomerInterface $customerTransfer)
    {
        foreach ($this->passwordRestoredConfirmationSender as $sender) {
            $sender->send($customerTransfer->getEmail());
        }
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return CustomerInterface
     */
    public function confirmRegistration(CustomerInterface $customerTransfer)
    {
        $customerEntity = $this->queryContainer->queryCustomerByRegistrationKey($customerTransfer->getRegistrationKey())
            ->findOne()
        ;
        if (null === $customerEntity) {
            throw new CustomerNotFoundException('Customer not found.');
        }

        $customerEntity->setRegistered(new \DateTime('now', new \DateTimeZone(Config::get(SystemConfig::PROJECT_TIMEZONE))));
        $customerEntity->setRegistrationKey(null);

        $customerEntity->save();
        $customerTransfer->fromArray($customerEntity->toArray());

        return $customerTransfer;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return bool
     */
    public function forgotPassword(CustomerInterface $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);
        $customerEntity->setRestorePasswordDate(new \DateTime('now', new \DateTimeZone(Config::get(SystemConfig::PROJECT_TIMEZONE))));
        $customerEntity->setRestorePasswordKey($this->generateKey());

        $customerEntity->save();

        $customerTransfer->fromArray($customerEntity->toArray());
        $this->sendPasswordRestoreToken($customerTransfer);

        return true;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws PropelException
     * @throws CustomerNotFoundException
     *
     * @return bool
     */
    public function restorePassword(CustomerInterface $customerTransfer)
    {
        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customerEntity = $this->getCustomer($customerTransfer);

        $customerEntity->setRestorePasswordDate(null);
        $customerEntity->setRestorePasswordKey(null);

        $customerEntity->setPassword($customerTransfer->getPassword());

        $customerEntity->save();
        $this->sendPasswordRestoreConfirmation($customerTransfer);

        return true;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws PropelException
     * @throws CustomerNotFoundException
     *
     * @return bool
     */
    public function delete(CustomerInterface $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);
        $customerEntity->delete();

        return true;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws PropelException
     * @throws CustomerNotFoundException
     * @throws CustomerNotUpdatedException
     *
     * @return CustomerResponseTransfer
     */
    public function update(CustomerInterface $customerTransfer)
    {
        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customerEntity = $this->getCustomer($customerTransfer);
        $customerEntity->fromArray($customerTransfer->toArray());

        if (!$this->isEmailAvailableForCustomer($customerEntity)) {
            $customerResponseTransfer = $this->createCustomerEmailAlreadyUsedResponse();

            return $customerResponseTransfer;
        }

        $customerResponseTransfer = $this->createCustomerResponseTransfer();

        $changedRows = $customerEntity->save();

        $customerResponseTransfer
            ->setIsSuccess($changedRows > 0)
            ->setCustomerTransfer($customerTransfer)
        ;

        $this->sendRegistrationToken($customerTransfer);

        return $customerResponseTransfer;
    }

    /**
     * @param bool $isSuccess
     *
     * @return CustomerResponseTransfer
     */
    protected function createCustomerResponseTransfer($isSuccess = true)
    {
        $customerResponseTransfer = new CustomerResponseTransfer();
        $customerResponseTransfer->setIsSuccess($isSuccess);

        return $customerResponseTransfer;
    }

    /**
     * @return CustomerResponseTransfer
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
     * @param SpyCustomer $customerEntity
     *
     * @return bool
     */
    protected function isEmailAvailableForCustomer(SpyCustomer $customerEntity)
    {
        $count = $this->queryContainer
            ->queryCustomerByEmailApartFromIdCustomer($customerEntity->getEmail(), $customerEntity->getIdCustomer())
            ->count()
        ;

        return ($count === 0);
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @throws PropelException
     * @throws CustomerNotFoundException
     * @throws CustomerNotUpdatedException
     *
     * @return CustomerResponseTransfer
     */
    public function updatePassword(CustomerInterface $customerTransfer)
    {
        $customerEntity = $this->getCustomer($customerTransfer);

        $customerResponseTransfer = $this->getCustomerPasswordInvalidResponse($customerEntity, $customerTransfer);
        if (!$customerResponseTransfer->getIsSuccess()) {
            return $customerResponseTransfer;
        }

        $customerTransfer = $this->encryptNewPassword($customerTransfer);
        $customerTransfer->setPassword($customerTransfer->getNewPassword());

        $customerEntity->fromArray($customerTransfer->toArray());

        $changedRows = $customerEntity->save();

        $customerResponseTransfer
            ->setIsSuccess($changedRows > 0)
            ->setCustomerTransfer($customerTransfer)
        ;

        return $customerResponseTransfer;
    }

    /**
     * @param SpyCustomer $customerEntity
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerResponseTransfer
     */
    protected function getCustomerPasswordInvalidResponse(SpyCustomer $customerEntity, CustomerInterface $customerTransfer)
    {
        $customerResponseTransfer = new CustomerResponseTransfer();
        $customerResponseTransfer->setIsSuccess(true);

        if (!$this->isValidPassword($customerEntity->getPassword(), $customerTransfer->getPassword())) {
            $customerErrorTransfer = new CustomerErrorTransfer();
            $customerErrorTransfer
                ->setMessage(Messages::CUSTOMER_PASSWORD_INVALID)
            ;
            $customerResponseTransfer
                ->setIsSuccess(false)
                ->addError($customerErrorTransfer)
            ;
        }

        return $customerResponseTransfer;
    }

    /**
     * @param SpyCustomerAddress $customer
     *
     * @return AddressInterface
     */
    protected function entityToTransfer(SpyCustomerAddress $customer)
    {
        $entity = new AddressTransfer();

        return $entity->fromArray($customer->toArray(), true);
    }

    /**
     * @param ObjectCollection $entities
     * @param SpyCustomer $customer
     *
     * @return AddressesTransfer
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
     * @param CustomerInterface $customerTransfer
     *
     * @throws CustomerNotFoundException
     *
     * @return SpyCustomer
     */
    protected function getCustomer(CustomerInterface $customerTransfer)
    {
        $customerEntity = null;

        if ($customerTransfer->getIdCustomer()) {
            $customerEntity = $this->queryContainer->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne()
            ;
        } elseif ($customerTransfer->getEmail()) {
            $customerEntity = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne()
            ;
        } elseif ($customerTransfer->getRestorePasswordKey()) {
            $customerEntity = $this->queryContainer->queryCustomerByRestorePasswordKey($customerTransfer->getRestorePasswordKey())
                ->findOne()
            ;
        }

        if (null !== $customerEntity) {
            return $customerEntity;
        }

        throw new CustomerNotFoundException();
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return bool
     */
    protected function hasCustomer(CustomerInterface $customerTransfer)
    {
        $result = false;
        $customerEntity = null;

        if ($customerTransfer->getIdCustomer()) {
            $customerEntity = $this->queryContainer
                ->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne()
            ;
        } elseif ($customerTransfer->getEmail()) {
            $customerEntity = $this->queryContainer
                ->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne()
            ;
        }

        if (null !== $customerEntity) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return bool
     */
    public function tryAuthorizeCustomerByEmailAndPassword(CustomerInterface $customerTransfer)
    {
        $result = false;

        $customerEntity = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
            ->findOne()
        ;

        if (null !== $customerEntity) {
            $result = $this->isValidPassword($customerEntity->getPassword(), $customerTransfer->getPassword());
        }

        return $result;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    protected function encryptPassword(CustomerInterface $customerTransfer)
    {
        $currentPassword = $customerTransfer->getPassword();
        $customerTransfer->setPassword($this->getEncodedPassword($currentPassword));

        return $customerTransfer;
    }

    /**
     * @param CustomerInterface $customerTransfer
     *
     * @return CustomerInterface
     */
    protected function encryptNewPassword(CustomerInterface $customerTransfer)
    {
        $currentPassword = $customerTransfer->getNewPassword();
        $customerTransfer->setNewPassword($this->getEncodedPassword($currentPassword));

        return $customerTransfer;
    }

    /**
     * @param $currentPassword
     *
     * @return string
     */
    protected function getEncodedPassword($currentPassword)
    {
        $newPassword = $currentPassword;

        if ('$2' !== mb_substr($currentPassword, 0, 2)) {
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
