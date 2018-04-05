<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanySupplier\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyTypeBuilder;
use Generated\Shared\Transfer\CompanyTypeTransfer;
use Orm\Zed\CompanySupplier\Persistence\SpyCompanyTypeQuery;

class CompanySupplierHelper extends Module
{
    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyTypeTransfer
     */
    public function haveCompanyType(array $seedData = []): CompanyTypeTransfer
    {
        $companyTypeTransfer = (new CompanyTypeBuilder($seedData))->build();
        $companyTypeQuery = new SpyCompanyTypeQuery();
        $companyTypeQuery->filterByName($companyTypeTransfer->getName());
        $companyTypeEntity = $companyTypeQuery->findOneOrCreate();
        $companyTypeEntity->save();
        $companyTypeTransfer->fromArray($companyTypeEntity->toArray(), true);

        return $companyTypeTransfer;
    }
}
