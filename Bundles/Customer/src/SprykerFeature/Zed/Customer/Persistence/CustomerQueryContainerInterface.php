<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Customer\Persistence;

use Propel\Runtime\Exception\PropelException;
use SprykerEngine\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerAddressQuery;
use SprykerFeature\Zed\Customer\Persistence\Propel\SpyCustomerQuery;
use SprykerFeature\Zed\Sales\Persistence\Propel\SpySalesOrderQuery;

interface CustomerQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @param string $email
     *
     * @return SpyCustomerQuery
     */
    public function queryCustomerByEmail($email);

    /**
     * @param int $id
     *
     * @return SpyCustomerQuery
     */
    public function queryCustomerById($id);

    /**
     * @param string $token
     *
     * @return SpyCustomerQuery
     */
    public function queryCustomerByRegistrationKey($token);

    /**
     * @param string $token
     *
     * @return SpyCustomerQuery
     */
    public function queryCustomerByRestorePasswordKey($token);

    /**
     * @param int $idAddress
     * @param string $email
     *
     * @throws PropelException
     *
     * @return SpyCustomerAddressQuery
     */
    public function queryAddressForCustomer($idAddress, $email);

    /**
     * @param int $idAddress
     *
     * @throws PropelException
     *
     * @return SpyCustomerAddressQuery
     */
    public function queryAddress($idAddress);

    /**
     * @param string $email
     *
     * @return SpyCustomerAddressQuery
     */
    public function queryAddressesForCustomer($email);

    /**
     * @return SpyCustomerAddressQuery
     */
    public function queryAddresses();

    /**
     * @return SpyCustomerQuery
     */
    public function queryCustomers();

    /**
     * @param int $idCustomer
     *
     * @return SpySalesOrderQuery
     */
    public function queryOrdersByCustomerId($idCustomer);

}
