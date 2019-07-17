<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUsersRestApi\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;

interface CompanyUsersRestApiToCompanyUserStorageClientInterface
{
    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer|null
     */
    public function findCompanyUserByMapping(string $mappingType, string $identifier): ?CompanyUserStorageTransfer;
}
