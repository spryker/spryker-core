<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Business\Customer;

use Generated\Shared\Transfer\CustomerAddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Propel\Runtime\Collection\ObjectCollection;
use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Customer\Business\Exception\AddressNotFoundException;
use SprykerFeature\Zed\Customer\Business\Exception\CountryNotFoundException;
use SprykerFeature\Zed\Customer\Business\Exception\CustomerNotFoundException;
use SprykerFeature\Zed\Customer\Dependency\Facade\CustomerToCountryInterface;
use SprykerFeature\Zed\Customer\Dependency\Facade\CustomerToLocaleInterface;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainerInterface;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddress;

class Address
{

    /**
     * @var CustomerQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @var CustomerToCountryInterface
     */
    private $countryFacade;

    /**
     * @var CustomerToLocaleInterface
     */
    private $localeFacade;

    /**
     * @param QueryContainerInterface $queryContainer
     * @param CustomerToCountryInterface $countryFacade
     * @param CustomerToLocaleInterface $localeFacade
     */
    public function __construct(QueryContainerInterface $queryContainer, CustomerToCountryInterface $countryFacade, CustomerToLocaleInterface $localeFacade)
    {
        $this->queryContainer = $queryContainer;
        $this->countryFacade = $countryFacade;
        $this->localeFacade = $localeFacade;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return CustomerAddressTransfer
     */
    public function createAddress(CustomerAddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomerFromAddressTransfer($addressTransfer);

        $entity = new SpyCustomerAddress();
        $entity->fromArray($addressTransfer->toArray());
        $entity->setFkCountry($this->getCustomerCountryId());
        $entity->setCustomer($customer);
        $entity->save();

        if ($customer->getDefaultShippingAddress() === null) {
            $customer->setDefaultShippingAddress($entity->getIdCustomerAddress());
        }

        if ($customer->getDefaultBillingAddress() === null) {
            $customer->setDefaultBillingAddress($entity->getIdCustomerAddress());
        }

        $customer->save();

        $addressTransfer = $this->entityToTransfer($entity);

        return $addressTransfer;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @throws AddressNotFoundException
     *
     * @return CustomerAddressTransfer
     */
    public function getAddress(CustomerAddressTransfer $addressTransfer)
    {
        $entity = $this->queryContainer->queryAddress($addressTransfer->getIdCustomerAddress())
            ->findOne()
        ;

        if (!$entity) {
            throw new AddressNotFoundException();
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @throws AddressNotFoundException
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return CustomerAddressTransfer
     */
    public function updateAddress(CustomerAddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomerFromAddressTransfer($addressTransfer);

        $entity = $this->queryContainer->queryAddressForCustomer($addressTransfer->getIdCustomerAddress(), $customer->getEmail())
            ->findOne()
        ;

        if (!$entity) {
            throw new AddressNotFoundException();
        }

        $entity->fromArray($addressTransfer->toArray());
        $entity->setCustomer($customer);
        $entity->setFkCountry($this->getCustomerCountryId());
        $entity->save();

        return $this->entityToTransfer($entity);
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @throws AddressNotFoundException
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return bool
     */
    public function setDefaultShippingAddress(CustomerAddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomerFromAddressTransfer($addressTransfer);

        $entity = $this->queryContainer->queryAddressForCustomer($addressTransfer->getIdCustomerAddress(), $customer->getEmail())
            ->findOne()
        ;

        if (!$entity) {
            throw new AddressNotFoundException();
        }

        $customer->setDefaultShippingAddress($addressTransfer->getIdCustomerAddress());
        $customer->save();

        return true;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @throws AddressNotFoundException
     * @throws CustomerNotFoundException
     * @throws PropelException
     *
     * @return bool
     */
    public function setDefaultBillingAddress(CustomerAddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomerFromAddressTransfer($addressTransfer);

        $entity = $this->queryContainer->queryAddressForCustomer($addressTransfer->getIdCustomerAddress(), $customer->getEmail())
            ->findOne()
        ;

        if (!$entity) {
            throw new AddressNotFoundException();
        }

        $customer->setDefaultBillingAddress($addressTransfer->getIdCustomerAddress());
        $customer->save();

        return true;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return string
     */
    public function getFormattedAddressString(CustomerAddressTransfer $addressTransfer)
    {
        return implode("\n", $this->getFormattedAddressArray($addressTransfer));
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @return array
     */
    public function getFormattedAddressArray(CustomerAddressTransfer $addressTransfer)
    {
        $address = [];

        if (count($addressTransfer->getCompany()) > 0) {
            $address[] = $addressTransfer->getCompany();
        }

        $address[] = sprintf('%s %s %s', $addressTransfer->getSalutation(), $addressTransfer->getFirstName(), $addressTransfer->getLastName());

        if (count($addressTransfer->getAddress1()) > 0) {
            $address[] = $addressTransfer->getAddress1();
        }
        if (count($addressTransfer->getAddress2()) > 0) {
            $address[] = $addressTransfer->getAddress2();
        }
        if (count($addressTransfer->getAddress3()) > 0) {
            $address[] = $addressTransfer->getAddress3();
        }

        $address[] = sprintf('%s %s', $addressTransfer->getZipCode(), $addressTransfer->getCity());

        return $address;
    }

    /**
     * @param SpyCustomerAddress $entity
     *
     * @return CustomerAddressTransfer
     */
    protected function entityToTransfer(SpyCustomerAddress $entity)
    {
        $data = $entity->toArray();
        unset($data['deleted_at']);
        unset($data['created_at']);
        unset($data['updated_at']);
        $addressTransfer = new CustomerAddressTransfer();
        $addressTransfer->fromArray($data);

        return $addressTransfer;
    }

    /**
     * @param ObjectCollection $entities
     *
     * @throws AddressNotFoundException
     *
     * @return CustomerAddressTransfer
     */
    protected function entityCollectionToTransferCollection(ObjectCollection $entities)
    {
        $addresses = [];
        foreach ($entities->getData() as $entity) {
            $addresses[] = $this->entityToTransfer($entity);
        }
        $addressTransferCollection = new CustomerAddressTransfer();
        $addressTransferCollection->fromArray($addresses);

        return $addressTransferCollection;
    }

    /**
     * @param CustomerAddressTransfer $addressTransfer
     *
     * @throws CustomerNotFoundException
     *
     * @return SpyCustomer
     */
    protected function getCustomerFromAddressTransfer(CustomerAddressTransfer $addressTransfer)
    {
        if ($addressTransfer->getEmail()) {
            $customer = $this->queryContainer->queryCustomerByEmail($addressTransfer->getEmail())
                ->findOne()
            ;
        } elseif ($addressTransfer->getFkCustomer()) {
            $customer = $this->queryContainer->queryCustomerById($addressTransfer->getFkCustomer())
                ->findOne()
            ;
        }

        if (!isset($customer) || $customer === null) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @throws CustomerNotFoundException
     *
     * @return SpyCustomer
     */
    protected function getCustomerFromCustomerTransfer(CustomerTransfer $customerTransfer)
    {
        if ($customerTransfer->getEmail()) {
            $customer = $this->queryContainer->queryCustomerByEmail($customerTransfer->getEmail())
                ->findOne()
            ;
        } elseif ($customerTransfer->getIdCustomer()) {
            $customer = $this->queryContainer->queryCustomerById($customerTransfer->getIdCustomer())
                ->findOne()
            ;
        }

        if (!isset($customer) || $customer === null) {
            throw new CustomerNotFoundException();
        }

        return $customer;
    }

    /**
     * @throws CountryNotFoundException
     *
     * @return int
     */
    protected function getCustomerCountryId()
    {
        $idCountry = $this->countryFacade->getIdCountryByIso2Code($this->getIsoCode());

        if (is_null($idCountry)) {
            throw new CountryNotFoundException();
        }

        return $idCountry;
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @throws AddressNotFoundException
     *
     * @return CustomerAddressTransfer
     */
    public function getDefaultShippingAddress(CustomerTransfer $customerTransfer)
    {
        $customer = $this->getCustomerFromCustomerTransfer($customerTransfer);
        $id_address = $customer->getDefaultShippingAddress();
        $address = $this->queryContainer->queryAddress($id_address)
            ->findOne()
        ;
        if ($address === null) {
            throw new AddressNotFoundException();
        }

        return $this->entityToTransfer($address);
    }

    /**
     * @param CustomerTransfer $customerTransfer
     *
     * @throws AddressNotFoundException
     *
     * @return CustomerAddressTransfer
     */
    public function getDefaultBillingAddress(CustomerTransfer $customerTransfer)
    {
        $customer = $this->getCustomerFromCustomerTransfer($customerTransfer);
        $id_address = $customer->getDefaultBillingAddress();
        $address = $this->queryContainer->queryAddress($id_address)
            ->findOne()
        ;
        if ($address === null) {
            throw new AddressNotFoundException();
        }

        return $this->entityToTransfer($address);
    }

    /**
     * @return string
     */
    private function getIsoCode()
    {
        $localeName = $this->localeFacade->getCurrentLocale()
            ->getLocaleName()
        ;

        return explode('_', $localeName)[1];
    }

}
