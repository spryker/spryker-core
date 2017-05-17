<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Customer\Persistence;

use Spryker\Zed\Kernel\Persistence\QueryContainer\QueryContainerInterface;

interface CustomerQueryContainerInterface extends QueryContainerInterface
{

    /**
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByEmail($email);

    /**
     * @api
     *
     * @param string $email
     * @param int $exceptIdCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByEmailApartFromIdCustomer($email, $exceptIdCustomer);

    /**
     * @api
     *
     * @param int $id
     * @param bool $withAnonymized
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerById($id, $withAnonymized = false);

    /**
     * @api
     *
     * @param string $token
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByRegistrationKey($token);

    /**
     * @api
     *
     * @param string $token
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByRestorePasswordKey($token);

    /**
     * @api
     *
     * @param int $idAddress
     * @param string $email
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressForCustomer($idAddress, $email);

    /**
     * @api
     *
     * @param int $idAddress
     *
     * @throws \Propel\Runtime\Exception\PropelException
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddress($idAddress);

    /**
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressesForCustomer($email);

    /**
     * @api
     *
     * @param bool $withAnonymized
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddresses($withAnonymized = false);

    /**
     * @api
     *
     * @param bool $withAnonymized
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomers($withAnonymized = false);

    /**
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressByIdCustomer($idCustomer);

}
