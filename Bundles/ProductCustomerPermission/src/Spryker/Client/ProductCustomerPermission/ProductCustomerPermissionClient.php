<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\ProductCustomerPermission;

use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\ProductCustomerPermission\ProductCustomerPermissionFactory getFactory()
 */
class ProductCustomerPermissionClient extends AbstractClient implements ProductCustomerPermissionClientInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param int $idCustomer
     * @param int $idProductAbstract
     *
     * @return bool
     */
    public function isAllowedForCustomer(int $idCustomer, int $idProductAbstract): bool
    {
        return $this->getFactory()
            ->createStorage()
            ->hasProductCustomerPermission($idCustomer, $idProductAbstract);
    }
}
