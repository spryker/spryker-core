<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
use Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CompanyUnitAddressDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer|\Spryker\Shared\Kernel\Transfer\AbstractTransfer
     */
    public function haveCompanyUnitAddress(array $seed = [])
    {
        $companyUnitAddressTransferBuilder = new CompanyUnitAddressBuilder($seed);
        $companyUnitAddressTransfer = $companyUnitAddressTransferBuilder->build();

        $this->ensureCompanyUnitAddressWithKeyDoesNotExist($companyUnitAddressTransfer->getKey());
        $this->getCompanyUnitAddressFacade()->create($companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
    }

    /**
     * @param string $key
     *
     * @return void
     */
    public function ensureCompanyUnitAddressWithKeyDoesNotExist(string $key): void
    {
        $companyUnitAddressQuery = $this->getCompanyUnitAddressQuery();
        $companyUnitAddressQuery->filterByKey($key)->delete();
    }

    /**
     * @return \Orm\Zed\CompanyUnitAddress\Persistence\SpyCompanyUnitAddressQuery
     */
    protected function getCompanyUnitAddressQuery(): SpyCompanyUnitAddressQuery
    {
        return SpyCompanyUnitAddressQuery::create();
    }

    /**
     * @return \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface|\Spryker\Zed\Kernel\Business\AbstractFacade
     */
    protected function getCompanyUnitAddressFacade()
    {
        return $this->getLocator()->companyUnitAddress()->facade();
    }
}
