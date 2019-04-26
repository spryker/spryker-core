<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;

interface CompanyUserStorageClientInterface
{
    /**
     * Specification:
     *  - Retrieves a CompanyUser resource from Storage using specified mapping.
     *  - Responds with null if company user data is not found in storage.
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer|null
     */
    public function findCompanyUserByMapping(string $mappingType, string $identifier): ?CompanyUserStorageTransfer;
}
