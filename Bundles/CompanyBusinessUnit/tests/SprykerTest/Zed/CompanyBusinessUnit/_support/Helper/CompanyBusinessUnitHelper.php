<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyBusinessUnit\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyBusinessUnitBuilder;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Orm\Zed\CompanyBusinessUnit\Persistence\SpyCompanyBusinessUnitQuery;
use Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;
use SprykerTest\Zed\Company\Helper\CompanyHelper;

class CompanyBusinessUnitHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seedData
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitTransfer
     */
    public function haveCompanyBusinessUnit(array $seedData = []): CompanyBusinessUnitTransfer
    {
        if (!isset($seedData['fkCompany'])) {
            $seedData['fkCompany'] = $this->getCompanyHelper()->haveCompany()->getIdCompany();
        }

        $companyBusinessUnitTransfer = (new CompanyBusinessUnitBuilder($seedData))->build();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit(null);

        $this->ensureCompanyBusinessUnitWithKeyDoesNotExist($companyBusinessUnitTransfer->getKey());

        return $this->getCompanyBusinessUnitFacade()
            ->create($companyBusinessUnitTransfer)
            ->getCompanyBusinessUnitTransfer();
    }

    /**
     * @return \Spryker\Zed\CompanyBusinessUnit\Business\CompanyBusinessUnitFacadeInterface
     */
    protected function getCompanyBusinessUnitFacade(): CompanyBusinessUnitFacadeInterface
    {
        return $this->getLocator()->companyBusinessUnit()->facade();
    }

    /**
     * @param string $key
     *
     * @return void
     */
    protected function ensureCompanyBusinessUnitWithKeyDoesNotExist(string $key): void
    {
        SpyCompanyBusinessUnitQuery::create()->filterByKey($key)->delete();
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Zed\Company\Helper\CompanyHelper
     */
    protected function getCompanyHelper(): CompanyHelper
    {
        return $this->getModule('\\' . CompanyHelper::class);
    }
}
