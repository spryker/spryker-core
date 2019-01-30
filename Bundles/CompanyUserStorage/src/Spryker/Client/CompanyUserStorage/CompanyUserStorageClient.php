<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\CompanyUserStorage;

use Generated\Shared\Transfer\CompanyUserStorageTransfer;
use Spryker\Client\Kernel\AbstractClient;

/**
 * @method \Spryker\Client\CompanyUserStorage\CompanyUserStorageFactory getFactory()
 */
class CompanyUserStorageClient extends AbstractClient implements CompanyUserStorageClientInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $mappingType
     * @param string $identifier
     *
     * @return \Generated\Shared\Transfer\CompanyUserStorageTransfer|null
     */
    public function findCompanyUserByMapping(string $mappingType, string $identifier): ?CompanyUserStorageTransfer
    {
        return $this->getFactory()
            ->createCompanyUserStorage()
            ->findCompanyUserByMapping($mappingType, $identifier);
    }
}
