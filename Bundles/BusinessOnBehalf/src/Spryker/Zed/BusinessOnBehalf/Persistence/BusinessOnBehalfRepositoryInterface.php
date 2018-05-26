<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

interface BusinessOnBehalfRepositoryInterface
{
    /**
     * Specification
     * - Checks is customer on behalf, i.e. has multiple company user accounts
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return bool
     */
    public function isOnBehalfByCustomerId(int $idCustomer): bool;
}
