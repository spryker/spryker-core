<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business;

interface CompanyUserStorageFacadeInterface
{
    /**
     * Specification:
     *  - Queries all active companyUser with the given companyUserIds;
     *  - Removes all inactive companyUser from storage;
     *  - Stores data as json encoded to storage table;
     *  - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param int[] $companyUserIds
     *
     * @return void
     */
    public function publishByCompanyUserIds(array $companyUserIds): void;

    /**
     * Specification:
     *  - Queries all active companyUser with the given companyIds;
     *  - Removes all inactive companyUser from storage;
     *  - Stores data as json encoded to storage table;
     *  - Sends a copy of data to queue based on module config.
     *
     * @api
     *
     * @param int[] $companyIds
     *
     * @return void
     */
    public function publishByCompanyIds(array $companyIds): void;

    /**
     * Specification:
     *  - Finds and deletes companyUser storage entities with the given companyUserIds;
     *  - Sends delete message to queue based on module config.
     *
     * @api
     *
     * @param int[] $companyUserIds
     *
     * @return void
     */
    public function unpublishByCompanyUserIds(array $companyUserIds): void;
}
