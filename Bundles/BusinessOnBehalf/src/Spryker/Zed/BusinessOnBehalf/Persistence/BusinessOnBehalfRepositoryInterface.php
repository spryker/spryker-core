<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\BusinessOnBehalf\Persistence;

use Generated\Shared\Transfer\CompanyUserTransfer;

interface BusinessOnBehalfRepositoryInterface
{
    /**
     * Specification:
     * - Checks is customer on behalf, i.e. has multiple company user accounts
     *
     * @api
     *
     * @param int $idCustomer
     *
     * @return bool
     */
    public function isOnBehalfByCustomerId(int $idCustomer): bool;

    /**
     * Specification:
     * - Retrieves ids of active company users by customer id
     *
     * @param int $idCustomer
     *
     * @return int[]
     */
    public function findActiveCompanyUserIdsByCustomerId(int $idCustomer): array;

    /**
     * @param int $idCustomer
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer|null
     */
    public function findDefaultCompanyUserByCustomerId(int $idCustomer): ?CompanyUserTransfer;
}
