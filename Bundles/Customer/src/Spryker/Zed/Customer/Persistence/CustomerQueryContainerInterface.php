<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Spryker\Zed\Customer\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CustomerQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $email
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByEmail($email);

    /**
     * @param string $email
     * @param int $exceptIdCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByEmailApartFromIdCustomer($email, $exceptIdCustomer);

    /**
     * @param int $id
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerById($id);

    /**
     * @param string $token
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByRegistrationKey($token);

    /**
     * @param string $token
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByRestorePasswordKey($token);

    /**
     * @param int $idAddress
     * @param string $email
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressForCustomer($idAddress, $email);

    /**
     * @param int $idAddress
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddress($idAddress);

    /**
     * @param string $email
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressesForCustomer($email);

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddresses();

    /**
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomers();

    /**
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressByIdCustomer($idCustomer);

}
