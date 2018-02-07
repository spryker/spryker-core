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
     * @param int $idProduct
     *
     * @return void
     */
    public function savePermission(int $idCustomer, int $idProduct);

    /**
     * @param int $idCustomer
     * @param int[] $idProductAbstracts
     *
     * @return void
     */
    public function savePermissions(int $idCustomer, array $idProductAbstracts);

    /**
     * @param int $idCustomer
     * @param int $idProduct
     *
     * @return void
     */
    public function deletePermission(int $idCustomer, int $idProduct);

    /**
     * @param int $idCustomer
     *
     * @return void
     */
    public function deleteAllPermissions(int $idCustomer);

    /**
     * @param int $idCustomer
     * @param int[] $idProductAbstracts
     *
     * @return void
     */
    public function deletePermissions(int $idCustomer, array $idProductAbstracts);
}
