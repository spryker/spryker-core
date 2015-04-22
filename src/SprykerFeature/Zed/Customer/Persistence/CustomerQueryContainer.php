<?php

namespace SprykerFeature\Zed\Customer\Persistence;

use SprykerEngine\Zed\Kernel\Persistence\AbstractQueryContainer;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;
use Propel\Runtime\Exception\PropelException;

class CustomerQueryContainer extends AbstractQueryContainer
{
    /**
     * @param string $email
     *
     * @return SpyCustomerQuery
     */
    public function getCustomerByEmail($email)
    {
        $query = $this->getDependencyContainer()->createSpyCustomerQuery();
        $query->filterByEmail($email);

        return $query;
    }

    /**
     * @param int $id
     *
     * @return SpyCustomerQuery
     */
    public function getCustomerById($id)
    {
        $query = $this->getDependencyContainer()->createSpyCustomerQuery();
        $query->filterByIdCustomer($id);

        return $query;
    }

    /**
     * @param string $token
     *
     * @return SpyCustomerQuery
     */
    public function getCustomerByRegistrationKey($token)
    {
        $query = $this->getDependencyContainer()->createSpyCustomerQuery();
        $query->filterByRegistrationKey($token);

        return $query;
    }

    /**
     * @param string $token
     *
     * @return SpyCustomerQuery
     */
    public function getCustomerByRestorePasswordKey($token)
    {
        $query = $this->getDependencyContainer()->createSpyCustomerQuery();
        $query->filterByRestorePasswordKey($token);

        return $query;
    }

    /**
     * @param int $addressId
     * @param string $email
     *
     * @return SpyCustomerAddressQuery
     * @throws PropelException
     */
    public function getAddressForCustomer($addressId, $email)
    {
        $customer = $this->getCustomerByEmail($email)->findOne();

        $query = $this->getDependencyContainer()->createSpyCustomerAddressQuery();
        $query->filterByIdCustomerAddress($addressId);
        $query->filterByCustomer($customer);

        return $query;
    }

    /**
     * @param int $addressId
     *
     * @return SpyCustomerAddressQuery
     * @throws PropelException
     */
    public function getAddress($addressId)
    {
        $query = $this->getDependencyContainer()->createSpyCustomerAddressQuery();
        $query->filterByIdCustomerAddress($addressId);

        return $query;
    }

    /**
     * @param string $email
     *
     * @return SpyCustomerAddressQuery
     */
    public function getAddressesForCustomer($email)
    {
        $customer = $this->getCustomerByEmail($email)->findOne();

        $query = $this->getDependencyContainer()->createSpyCustomerAddressQuery();
        $query->filterByCustomer($customer);

        return $query;
    }

    /**
     * @return SpyCustomerAddressQuery
     */
    public function getAddresses()
    {
        $query = $this->getDependencyContainer()->createSpyCustomerAddressQuery();

        return $query;
    }

    /**
     * @return SpyCustomerQuery
     */
    public function getCustomers()
    {
        $query = $this->getDependencyContainer()->createSpyCustomerQuery();

        return $query;
    }
}
