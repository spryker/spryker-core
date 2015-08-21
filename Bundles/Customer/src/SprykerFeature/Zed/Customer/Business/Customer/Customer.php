<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\Customer;

use Generated\Shared\Customer\CustomerInterface as CustomerTransferInterface;
use Generated\Shared\Customer\CustomerAddressInterface as CustomerAddressTransferInterface;
use Generated\Shared\Transfer\AddressesTransfer;
use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\FilterTransfer;
use Generated\Shared\Transfer\OrderTransfer;
use Propel\Runtime\ActiveQuery\Criteria;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Config;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerEngine\Zed\Propel\PropelFilterCriteria;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Customer\Business\Exception\CustomerNotFoundException;
use SprykerFeature\Zed\Customer\Business\Exception\CustomerNotUpdatedException;
use SprykerFeature\Zed\Customer\Business\Exception\EmailAlreadyRegisteredException;
use SprykerFeature\Zed\Customer\Business\ReferenceGenerator\CustomerReferenceGeneratorInterface;
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
     * @var string
     */
    protected $hostYves = '';

    /**
     * @param QueryContainerInterface $queryContainer
     * @param CustomerReferenceGeneratorInterface $customerReferenceGenerator
     * @param string $hostYves
     */
    public function __construct(QueryContainerInterface $queryContainer, CustomerReferenceGeneratorInterface $customerReferenceGenerator, $hostYves)
    {
        $this->queryContainer = $queryContainer;
        $this->customerReferenceGenerator = $customerReferenceGenerator;
        $this->hostYves = $hostYves;
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
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    public function get(CustomerTransferInterface $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);
        $customerTransfer->fromArray($customer->toArray());
        $addresses = $customer->getAddresses();
        if ($addresses) {
            $customerTransfer->setAddresses($this->entityCollectionToTransferCollection($addresses, $customer));
        }

        return $customerTransfer;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @throws EmailAlreadyRegisteredException
     * @throws PropelException
     *
     * @return CustomerTransferInterface
     */
    public function register(CustomerTransferInterface $customerTransfer)
    {
        if ($this->hasCustomer($customerTransfer)) {
            throw new EmailAlreadyRegisteredException();
        }

        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customer = new SpyCustomer();

        $customer->fromArray($customerTransfer->toArray());

        $customer->setCustomerReference($this->customerReferenceGenerator->generateCustomerReference($customerTransfer));
        $customer->setRegistrationKey($this->generateKey());

        $customer->save();

        $customerTransfer->setIdCustomer($customer->getPrimaryKey());
        $customerTransfer->setCustomerReference($customer->getCustomerReference());
        $customerTransfer->setRegistrationKey($customer->getRegistrationKey());

        $this->sendRegistrationToken($customerTransfer);

        return $customerTransfer;
    }

    /**
     * @return string
     */
    protected function generateKey()
    {
        return uniqid();
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     */
    protected function sendPasswordRestoreToken(CustomerTransferInterface $customerTransfer)
    {
        $customerTransfer = $this->get($customerTransfer);
        $link = $this->hostYves . '/password/restore?token=' . $customerTransfer->getRestorePasswordKey();
        foreach ($this->passwordRestoreTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $link);
        }
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     */
    protected function sendRegistrationToken(CustomerTransferInterface $customerTransfer)
    {
        $link = $this->hostYves . '/register/confirm?token=' . $customerTransfer->getRegistrationKey();
        foreach ($this->registrationTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $link);
        }
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     */
    protected function sendPasswordRestoreConfirmation(CustomerTransferInterface $customerTransfer)
    {
        foreach ($this->passwordRestoredConfirmationSender as $sender) {
            $sender->send($customerTransfer->getEmail());
        }
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return CustomerTransferInterface
     */
    public function confirmRegistration(CustomerTransferInterface $customerTransfer)
    {
        $customer = $this->queryContainer->queryCustomerByRegistrationKey($customerTransfer->getRegistrationKey())
            ->findOne()
        ;
        if (null === $customer) {
            throw new CustomerNotFoundException('Customer not found.');
        }

        $customer->setRegistered(new \DateTime('now', new \DateTimeZone(Config::get(SystemConfig::PROJECT_TIMEZONE))));
        $customer->setRegistrationKey(null);

        $customer->save();
        $customerTransfer->fromArray($customer->toArray());

        return $customerTransfer;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return bool
     */
    public function forgotPassword(CustomerTransferInterface $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);
        $customer->setRestorePasswordDate(new \DateTime('now', new \DateTimeZone(Config::get(SystemConfig::PROJECT_TIMEZONE))));
        $customer->setRestorePasswordKey($this->generateKey());

        $customer->save();

        $customerTransfer->fromArray($customer->toArray());
        $this->sendPasswordRestoreToken($customerTransfer);

        return true;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @throws PropelException
     * @throws CustomerNotFoundException
     *
     * @return bool
     */
    public function restorePassword(CustomerTransferInterface $customerTransfer)
    {
        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customer = $this->getCustomer($customerTransfer);

        $customer->setRestorePasswordDate(null);
        $customer->setRestorePasswordKey(null);

        $customer->setPassword($customerTransfer->getPassword());

        $customer->save();
        $this->sendPasswordRestoreConfirmation($customerTransfer);

        return true;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @throws PropelException
     * @throws CustomerNotFoundException
     *
     * @return bool
     */
    public function delete(CustomerTransferInterface $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);
        $customer->delete();

        return true;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     * 
     * @return CustomerTransferInterface
     */
    public function getOrders(CustomerTransferInterface $customerTransfer)
    {
        /**
         * @var FilterTransfer $filter
         */
        $filter = $customerTransfer->getFilter();
        $criteria = new Criteria();
        
        if (null !== $filter) {
            $criteria = (new PropelFilterCriteria($filter))
                ->toCriteria();
        }
        
        $orders = $this->queryContainer->queryordersByCustomerId($customerTransfer->getIdCustomer(), $criteria)
            ->find();

        $result = [];
        foreach ($orders as $orderItem) {
            $result[] = (new OrderTransfer())
                ->fromArray($orderItem->toArray());
        }

        $customerTransfer->setOrders(new \ArrayObject($result));

        return $customerTransfer;
    }

    /**
     * FIXME KSP-430 @spryker: this fails since the function encryptPassword() cannot be found!
     * //$customerTransfer = $this->encryptPassword($customerTransfer);
     *
     * @param CustomerTransferInterface $customerTransfer
     *
     * @throws PropelException
     * @throws CustomerNotFoundException
     * @throws CustomerNotUpdatedException
     *
     * @return bool
     */
    public function update(CustomerTransferInterface $customerTransfer)
    {
        $customerTransfer = $this->encryptPassword($customerTransfer);

        $customer = $this->getCustomer($customerTransfer);
        $customer->fromArray($customerTransfer->toArray());

        $changedRows = $customer->save();

        return ($changedRows > 0);
    }

    /**
     * @param SpyCustomerAddress $customer
     *
     * @return CustomerAddressTransferInterface
     */
    protected function entityToTransfer(SpyCustomerAddress $customer)
    {
        $entity = new CustomerAddressTransfer();
        return $entity->fromArray($customer->toArray(), true);
    }

    /**
     * @param ObjectCollection $entities
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

            $addressCollection->addCustomerAddress($addressTransfer);
        }

        return $addressCollection;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @throws CustomerNotFoundException
     *
     * @return SpyCustomer
     */
    protected function getCustomer(CustomerTransferInterface $customerTransfer)
    {
        $customer = null;
        
        if ($customerTransfer->getIdCustomer()) {
            $customer = $this->queryContainer->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne()
            ;
        } elseif ($customerTransfer->getEmail()) {
            $customer = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne()
            ;
        } elseif ($customerTransfer->getRestorePasswordKey()) {
            $customer = $this->queryContainer->queryCustomerByRestorePasswordKey($customerTransfer->getRestorePasswordKey())
                ->findOne()
            ;
        }

        if (null !== $customer) {
            return $customer;
        }

        throw new CustomerNotFoundException();
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return bool
     */
    protected function hasCustomer(CustomerTransferInterface $customerTransfer)
    {
        $result = false;
        $customer = null;

        if ($customerTransfer->getIdCustomer()) {
            $customer = $this->queryContainer
                ->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne()
            ;
        } elseif ($customerTransfer->getEmail()) {
            $customer = $this->queryContainer
                ->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne()
            ;
        }

        if (null !== $customer) {
            $result = true;
        }

        return $result;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return bool
     */
    public function tryAuthorizeCustomerByEmailAndPassword(CustomerTransferInterface $customerTransfer)
    {
        $result = false;

        $customer = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
            ->findOne()
        ;

        if (null !== $customer) {
            $result = $this->isValidPassword($customer->getPassword(), $customerTransfer->getPassword());
        }

        return $result;
    }

    /**
     * @param CustomerTransferInterface $customerTransfer
     *
     * @return CustomerTransferInterface
     */
    protected function encryptPassword(CustomerTransferInterface $customerTransfer)
    {
        $currentPassword = $customerTransfer->getPassword();

        if ('$2' !== mb_substr($currentPassword, 0, 2)) {
            $encoder = new BCryptPasswordEncoder(self::BCRYPT_FACTOR);

            $newPassword = $encoder->encodePassword($currentPassword, self::BCRYPT_SALT);
            $customerTransfer->setPassword($newPassword);
        }

        return $customerTransfer;
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
