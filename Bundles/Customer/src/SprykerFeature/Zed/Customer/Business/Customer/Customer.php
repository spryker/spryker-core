<?php

namespace SprykerFeature\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerCustomerTransfer;
use SprykerFeature\Shared\System\SystemConfig;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddress;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainer;
use Propel\Runtime\Collection\ObjectCollection;
use Generated\Shared\Transfer\CustomerAddressTransfer;
use DateTime;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Generated\Zed\Ide\AutoCompletion;
use SprykerFeature\Zed\Customer\Dependency\Plugin\RegistrationTokenSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoreTokenSenderPluginInterface;
use SprykerFeature\Zed\Customer\Dependency\Plugin\PasswordRestoredConfirmationSenderPluginInterface;
use SprykerFeature\Zed\Customer\Business\Exception\CustomerNotFoundException;
use SprykerFeature\Zed\Customer\Business\Exception\CustomerNotUpdatedException;
use SprykerFeature\Zed\Customer\Business\Exception\EmailAlreadyRegisteredException;

class Customer
{
    /** @var CustomerQueryContainer */
    protected $queryContainer;

    /** @var AutoCompletion */
    protected $locator;

    /** @var PasswordRestoredConfirmationSenderPluginInterface */
    protected $passwordRestoredConfirmationSender = [];

    /** @var PasswordRestoreTokenSenderPluginInterface */
    protected $passwordRestoreTokenSender = [];

    /** @var RegistrationTokenSenderPluginInterface */
    protected $registrationTokenSender = [];

    /**
     * @param QueryContainerInterface $queryContainer
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(QueryContainerInterface $queryContainer, LocatorLocatorInterface $locator)
    {
        $this->locator = $locator;
        $this->queryContainer = $queryContainer;
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
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     */
    public function get(CustomerTransfer $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);

        $customerTransfer->fromArray($customer->toArray());
        $addresses = $customer->getAddresses();
        if ($addresses) {
            $customerTransfer->setAddresses(
                $this->entityCollectionToTransferCollection($addresses)
            );
        }

        return $customerTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     * @throws EmailAlreadyRegisteredException
     * @throws PropelException
     */
    public function register(CustomerTransfer $customerTransfer)
    {
        try {
            $this->getCustomer($customerTransfer);
        } catch (CustomerNotFoundException $e) {
            $customerTransfer->setRegistrationKey($this->generateKey());
            $customer = new SpyCustomer();
            $customer->setPassword($customerTransfer->getPassword());
            $customer->setEmail($customerTransfer->getEmail());
            $customer->setRegistrationKey($customerTransfer->getRegistrationKey());
            $customer->save();
            $customerTransfer->setIdCustomer($customer->getPrimaryKey());
            $this->sendRegistrationToken($customerTransfer);

            return $customerTransfer;
        }

        throw new EmailAlreadyRegisteredException;
    }

    /**
     * @return string
     */
    protected function generateKey()
    {
        return uniqid();
    }

    /**
     * @param CustomerTransfer $customerTransfer
     */
    protected function sendPasswordRestoreToken(CustomerTransfer $customerTransfer)
    {
        $customerTransfer = $this->get($customerTransfer);
        $link = SystemConfig::HOST_YVES."/password/restore?token=".$customerTransfer->getRestorePasswordKey();
        foreach ($this->passwordRestoreTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $link);
        }
    }

    /**
     * @param CustomerTransfer $customerTransfer
     */
    protected function sendRegistrationToken(CustomerTransfer $customerTransfer)
    {
        $link = SystemConfig::HOST_YVES."/register/confirm?token=".$customerTransfer->getRegistrationKey();
        foreach ($this->registrationTokenSender as $sender) {
            $sender->send($customerTransfer->getEmail(), $link);
        }
    }

    /**
     * @param CustomerTransfer $customerTransfer
     */
    protected function sendPasswordRestoreConfirmation(CustomerTransfer $customerTransfer)
    {
        foreach ($this->passwordRestoredConfirmationSender as $sender) {
            $sender->send($customerTransfer->getEmail());
        }
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return CustomerTransfer
     * @throws CustomerNotFoundException
     * @throws PropelException
     */
    public function confirmRegistration(CustomerTransfer $customerTransfer)
    {
        $customer = $this->queryContainer
            ->queryCustomerByRegistrationKey($customerTransfer->getRegistrationKey())
            ->findOne();
        if (!$customer) {
            throw new CustomerNotFoundException('Customer not found.');
        }

        $customer->setRegistered(new DateTime());
        $customer->setRegistrationKey(null);
        $customer->save();
        $customerTransfer->fromArray($customer->toArray());

        return $customerTransfer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     * @throws CustomerNotFoundException
     * @throws PropelException
     */
    public function forgotPassword(CustomerTransfer $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);
        $customer->setRestorePasswordDate(new DateTime());
        $customer->setRestorePasswordKey($this->generateKey());
        $customer->save();
        $customerTransfer->fromArray($customer->toArray());
        $this->sendPasswordRestoreToken($customerTransfer);

        return true;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     * @throws PropelException
     * @throws CustomerNotFoundException
     */
    public function restorePassword(CustomerTransfer $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);
        $customer->setRestorePasswordDate(null);
        $customer->setRestorePasswordKey(null);
        $customer->setPassword($customerTransfer->getPassword());
        $customer->save();
        $this->sendPasswordRestoreConfirmation($customerTransfer);

        return true;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     * @throws PropelException
     * @throws CustomerNotFoundException
     */
    public function delete(CustomerTransfer $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);
        $customer->delete();

        return true;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return bool
     * @throws PropelException
     * @throws CustomerNotFoundException
     * @throws CustomerNotUpdatedException
     */
    public function update(CustomerTransfer $customerTransfer)
    {
        $customer = $this->getCustomer($customerTransfer);
        $customer->setFirstName($customerTransfer->getFirstName());
        $customer->setMiddleName($customerTransfer->getMiddleName());
        $customer->setLastName($customerTransfer->getLastName());
        $customer->setCompany($customerTransfer->getCompany());
        $customer->setDateOfBirth($customerTransfer->getDateOfBirth());
        $customer->setSalutation($customerTransfer->getSalutation());
        $customer->save();

        return true;
    }

    /**
     * @param SpyCustomerAddress $customer
     *
     * @return AddressTransfer
     */
    protected function entityToTransfer(SpyCustomerAddress $customer)
    {
        $data = $customer->toArray();
        unset($data["fk_misc_region"]);
        unset($data["deleted_at"]);
        unset($data["created_at"]);
        unset($data["updated_at"]);
        $addressTransfer = new \Generated\Shared\Transfer\CustomerAddressTransfer();
        $addressTransfer->fromArray($data);

        return $addressTransfer;
    }

    /**
     * @param ObjectCollection $entities
     *
     * @return AddressTransferCollection
     */
    protected function entityCollectionToTransferCollection(ObjectCollection $entities)
    {
        $addresses = [];
        foreach ($entities->getData() as $customer) {
            $addresses[] = $this->entityToTransfer($customer);
        }
        $addressTransferCollection = new \Generated\Shared\Transfer\CustomerAddressTransfer();
        $addressTransferCollection->fromArray($addresses);

        return $addressTransferCollection;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @return SpyCustomer
     * @throws CustomerNotFoundException
     */
    protected function getCustomer(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer->getIdCustomer()) {
            $customer = $this->queryContainer
                ->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne();
        } elseif ($customerTransfer->getEmail()) {
            $customer = $this->queryContainer
                ->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne();
        }

        if (isset($customer) && ($customer !== null)) {
            return $customer;
        }

        throw new CustomerNotFoundException;
    }
}
