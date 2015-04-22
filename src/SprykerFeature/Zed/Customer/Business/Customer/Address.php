<?php

namespace SprykerFeature\Zed\Customer\Business\Customer;

use SprykerFeature\Shared\Customer\Transfer\Address as AddressTransfer;
use Propel\Runtime\Exception\PropelException;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddress;
use SprykerFeature\Zed\Customer\Persistence\CustomerQueryContainer;
use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use Propel\Runtime\Collection\ObjectCollection;
use SprykerFeature\Shared\Customer\Transfer\AddressCollection as AddressTransferCollection;

class Address
{
    /** @var CustomerQueryContainer */
    protected $queryContainer;

    /** @var AutoCompletion */
    protected $locator;

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
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     * @throws PropelException
     */
    public function newAddress(AddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomer($addressTransfer);
        if (!$customer) {
            return false;
        }

        $entity = new SpyCustomerAddress();
        $entity->fromArray($addressTransfer->toArray());
        $entity->setFkCountry($this->getCustomerCountryId());
        $entity->setCustomer($customer);
        if (!$entity->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     * @throws \Exception
     */
    public function getAddress(AddressTransfer $addressTransfer)
    {
        $entity = $this->queryContainer
            ->getAddress(
                $addressTransfer->getIdCustomerAddress()
            )
            ->findOne();

        if (!$entity) {
            throw new \Exception('Address not found.');
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return AddressTransfer
     * @throws PropelException
     * @throws \Exception
     */
    public function updateAddress(AddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomer($addressTransfer);
        if (!$customer) {
            throw new \Exception('Customer not found.');
        }

        $entity = $this->queryContainer
            ->getAddressForCustomer(
                $addressTransfer->getIdCustomerAddress(),
                $customer->getEmail()
            )
            ->findOne();

        if (!$entity) {
            throw new \Exception('Address not found.');
        }

        $entity->fromArray($addressTransfer->toArray());
        $entity->setCustomer($customer);
        $country = $entity->getCountry();
        if (!$country) {
            $country = $this->getCustomerCountryId();
        }
        $entity->setCountry($country);

        if (!$entity->save()) {
            throw new \Exception('Unable to save Address.');
        }

        return $this->entityToTransfer($entity);
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     * @throws PropelException
     */
    public function setDefaultShippingAddress(AddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomer($addressTransfer);
        if (!$customer) {
            return false;
        }

        $entity = $this->queryContainer
            ->getAddressForCustomer(
                $addressTransfer->getIdCustomerAddress(),
                $customer->getEmail()
            )
            ->findOne();

        if (!$entity) {
            return false;
        }

        $customer->setDefaultShippingAddress($addressTransfer->getIdCustomerAddress());
        if (!$customer->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return bool
     * @throws PropelException
     */
    public function setDefaultBillingAddress(AddressTransfer $addressTransfer)
    {
        $customer = $this->getCustomer($addressTransfer);
        if (!$customer) {
            return false;
        }

        $entity = $this->queryContainer
            ->getAddressForCustomer(
                $addressTransfer->getIdCustomerAddress(),
                $customer->getEmail()
            )
            ->findOne();

        if (!$entity) {
            return false;
        }

        $customer->setDefaultBillingAddress($addressTransfer->getIdCustomerAddress());
        if (!$customer->save()) {
            return false;
        }

        return true;
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return string
     */
    public function getFormattedAddressString(AddressTransfer $addressTransfer)
    {
        return implode("\n", $this->getFormattedAddressArray($addressTransfer));
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return array
     */
    public function getFormattedAddressArray(AddressTransfer $addressTransfer)
    {
        $address = [];

        $address[] = sprintf(
            '%s %s',
            $addressTransfer->getSalutation(),
            $addressTransfer->getName()
        );

        if (count($addressTransfer->getAddress1()) > 0) {
            $address[] = $addressTransfer->getAddress1();
        }
        if (count($addressTransfer->getAddress2()) > 0) {
            $address[] = $addressTransfer->getAddress2();
        }
        if (count($addressTransfer->getAddress3()) > 0) {
            $address[] = $addressTransfer->getAddress3();
        }

        $address[] = sprintf(
            '%s %s',
            $addressTransfer->getZipCode(),
            $addressTransfer->getCity()
        );

        return $address;
    }

    /**
     * @param SpyCustomerAddress $entity
     *
     * @return AddressTransfer
     */
    protected function entityToTransfer(SpyCustomerAddress $entity)
    {
        $data = $entity->toArray();
        unset($data["deleted_at"]);
        unset($data["created_at"]);
        unset($data["updated_at"]);
        $addressTransfer = $this->locator->customer()->transferAddress();
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
        foreach ($entities->getData() as $entity) {
            $addresses[] = $this->entityToTransfer($entity);
        }
        $addressTransferCollection = $this->locator->customer()->transferAddressCollection();
        $addressTransferCollection->fromArray($addresses);

        return $addressTransferCollection;
    }

    /**
     * @param AddressTransfer $addressTransfer
     *
     * @return SpyCustomer
     */
    protected function getCustomer(AddressTransfer $addressTransfer)
    {
        $customer = $this->queryContainer
            ->getCustomerByEmail($addressTransfer->getEmail())
            ->findOne();
        if (!$customer) {
            $customer = $this->queryContainer
                ->getCustomerById($addressTransfer->getFkCustomer())
                ->findOne();
        }

        return $customer;
    }

    /**
     * @return int
     */
    protected function getCustomerCountryId()
    {
        $isoCode = explode(
            "_",
            $this->locator
                ->locale()
                ->facade()
                ->getCurrentLocale()
        )[1];

        $id_country = $this->locator
            ->country()
            ->facade()
            ->getIdCountryByIso2Code($isoCode);

        return $id_country;
    }
}
