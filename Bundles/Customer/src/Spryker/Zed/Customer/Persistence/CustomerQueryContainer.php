<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Persistence;

use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerPersistenceFactory getFactory()
 */
class CustomerQueryContainer extends AbstractQueryContainer implements CustomerQueryContainerInterface
{

    /**
     * @inheritdoc
     */
    public function queryCustomerByEmail($email)
    {
        $query = $this->getFactory()->createSpyCustomerQuery();
        $query->filterByEmail($email);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryCustomerByEmailApartFromIdCustomer($email, $exceptIdCustomer)
    {
        $query = $this->getFactory()->createSpyCustomerQuery();
        $query
            ->filterByEmail($email)
            ->filterByIdCustomer($exceptIdCustomer, Criteria::NOT_EQUAL);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryCustomerById($id)
    {
        $query = $this->getFactory()->createSpyCustomerQuery();
        $query->filterByIdCustomer($id);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryCustomerByRegistrationKey($token)
    {
        $query = $this->getFactory()->createSpyCustomerQuery();
        $query->filterByRegistrationKey($token);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryCustomerByRestorePasswordKey($token)
    {
        $query = $this->getFactory()->createSpyCustomerQuery();
        $query->filterByRestorePasswordKey($token);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryAddressForCustomer($idAddress, $email)
    {
        $customer = $this->queryCustomerByEmail($email)->findOne();

        $query = $this->getFactory()->createSpyCustomerAddressQuery();
        $query->filterByIdCustomerAddress($idAddress);
        $query->filterByCustomer($customer);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryAddressByIdCustomer($idCustomer)
    {
        return $this
            ->getFactory()
            ->createSpyCustomerAddressQuery()
            ->filterByFkCustomer($idCustomer);
    }

    /**
     * @inheritdoc
     */
    public function queryAddress($idAddress)
    {
        $query = $this->getFactory()->createSpyCustomerAddressQuery();
        $query->joinWithCountry();
        $query->filterByIdCustomerAddress($idAddress);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryAddressesForCustomer($email)
    {
        $customer = $this->queryCustomerByEmail($email)->findOne();

        $query = $this->getFactory()->createSpyCustomerAddressQuery();
        $query->filterByCustomer($customer);

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryAddresses()
    {
        $query = $this->getFactory()->createSpyCustomerAddressQuery();

        return $query;
    }

    /**
     * @inheritdoc
     */
    public function queryCustomers()
    {
        $query = $this->getFactory()->createSpyCustomerQuery();

        return $query;
    }

}
