<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business\Storage;

interface CompanyUserStorageWriterInterface
{
    /**
     * @param array<int> $companyUserIds
     *
     * @return void
     */
    public function publishByCompanyUserIds(array $companyUserIds): void;

    /**
     * @param array<int> $companyIds
     *
     * @return void
     */
    public function publishByCompanyIds(array $companyIds): void;

    /**
     * @param array<int> $companyUserIds
     *
     * @return void
     */
    public function unpublishByCompanyUserIds(array $companyUserIds): void;
}
