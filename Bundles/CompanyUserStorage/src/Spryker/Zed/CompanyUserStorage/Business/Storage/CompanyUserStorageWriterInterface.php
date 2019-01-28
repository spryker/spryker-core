<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CompanyUserStorage\Business\Storage;

interface CompanyUserStorageWriterInterface
{
    /**
     * @param int[] $companyUserIds
     *
     * @return void
     */
    public function publish(array $companyUserIds): void;

    /**
     * @param int[] $companyUserIds
     *
     * @return void
     */
    public function unpublish(array $companyUserIds): void;
}
