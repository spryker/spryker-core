<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductCustomerPermission\Business\Model;

interface ProductCustomerPermissionSaverInterface
{
    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function savePermission(int $idCustomer, int $idProductAbstract): void;

    /**
     * @param int $idCustomer
     * @param int[] $idProductAbstractAbstracts
     *
     * @return void
     */
    public function savePermissions(int $idCustomer, array $idProductAbstractAbstracts): void;

    /**
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return void
     */
    public function deletePermission(int $idCustomer, int $idProductAbstract): void;

    /**
     * @param int $idCustomer
     *
     * @return void
     */
    public function deleteAllPermissions(int $idCustomer): void;

    /**
     * @param int $idCustomer
     * @param int[] $idProductAbstractAbstracts
     *
     * @return void
     */
    public function deletePermissions(int $idCustomer, array $idProductAbstractAbstracts): void;
}
