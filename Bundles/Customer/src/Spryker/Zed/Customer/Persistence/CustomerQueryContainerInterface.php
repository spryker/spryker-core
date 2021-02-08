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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByEmail($email);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $email
     * @param int $exceptIdCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByEmailApartFromIdCustomer($email, $exceptIdCustomer);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $id
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerById($id);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $customerReference
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByReference($customerReference);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $token
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByRegistrationKey($token);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $token
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomerByRestorePasswordKey($token);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
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
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param string $email
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressesForCustomer($email);

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddresses();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerQuery
     */
    public function queryCustomers();

    /**
     * Specification:
     * - TODO: Add method specification.
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return \Orm\Zed\Customer\Persistence\SpyCustomerAddressQuery
     */
    public function queryAddressByIdCustomer($idCustomer);
}
