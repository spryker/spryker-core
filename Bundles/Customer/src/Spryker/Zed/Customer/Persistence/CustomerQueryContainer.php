<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Persistence;

use Generated\Shared\Transfer\AddressTransfer;
use Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\Persistence\AbstractQueryContainer;

/**
 * @method \Spryker\Zed\Customer\Persistence\CustomerPersistenceFactory getFactory()
 */
class CustomerQueryContainer extends AbstractQueryContainer implements CustomerQueryContainerInterface
{
    /**
     * @api
     *
     * @inheritdoc
     */
    public function queryCustomerByEmail($email)
    {
        $query = $this->queryCustomers();
        $query->filterByEmail($email);

        return $query;
    }

    /**
     * @api
     *
     * @inheritdoc
     */
    public function queryCustomerByEmailApartFromIdCustomer($email, $exceptIdCustomer)
    {
        $query = $this->queryCustomers();
        $query
            ->filterByEmail($email)
            ->filterByIdCustomer($exceptIdCustomer, Criteria::NOT_EQUAL);

        return $query;
    }

    /**
     * @api
     *
     * @inheritdoc
     */
    public function queryCustomerById($id)
    {
        $query = $this->queryCustomers();
        $query->filterByIdCustomer($id);

        return $query;
    }

    /**
     * @api
     *
     * @inheritdoc
     */
    public function queryCustomerByReference($customerReference)
    {
        $query = $this->queryCustomers();
        $query->filterByCustomerReference($customerReference);

        return $query;
    }

    /**
     * @api
     *
     * @inheritdoc
     */
    public function queryCustomerByRegistrationKey($token)
    {
        $query = $this->queryCustomers();
        $query->filterByRegistrationKey($token);

        return $query;
    }

    /**
     * @api
     *
     * @inheritdoc
     */
    public function queryCustomerByRestorePasswordKey($token)
    {
        $query = $this->queryCustomers();
        $query->filterByRestorePasswordKey($token);

        return $query;
    }

    /**
     * @api
     *
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
     * @api
     *
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
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressByTransfer(AddressTransfer $addressTransfer): SpyCustomerAddressQuery
    {
        return $this
            ->getFactory()
            ->createSpyCustomerAddressQuery()
            ->filterByFkCustomer($addressTransfer->getFkCustomer())
            ->filterByFirstName($addressTransfer->getFirstName())
            ->filterByLastName($addressTransfer->getLastName())
            ->filterByAddress1($addressTransfer->getAddress1())
            ->filterByAddress2($addressTransfer->getAddress2())
            ->filterByAddress3($addressTransfer->getAddress3())
            ->filterByZipCode($addressTransfer->getZipCode())
            ->filterByCity($addressTransfer->getCity())
            ->filterByFkCountry($addressTransfer->getFkCountry())
            ->filterByPhone($addressTransfer->getPhone());
    }

    /**
     * @api
     *
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
     * @api
     *
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
     * @api
     *
     * @inheritdoc
     */
    public function queryAddresses()
    {
        return $this->getFactory()->createSpyCustomerAddressQuery();
    }

    /**
     * @api
     *
     * @inheritdoc
     */
    public function queryCustomers()
    {
        return $this->getFactory()->createSpyCustomerQuery();
    }
}
