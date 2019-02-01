<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\CompanyUserAuthRestApi\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;

interface CompanyUserAuthRestApiToCompanyUserStorageClientInterface
{
    /**
     * @param string $mappingType
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer|null
     */
    public function findCompanyUserByMapping(string $mappingType, string $identifier): ?CompanyUserStorageTransfer;
}
