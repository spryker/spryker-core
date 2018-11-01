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
use SprykerTest\Zed\Country\Helper\CountryDataHelper;

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
        if (!isset($seed['fk_country'])) {
            $countryTransfer = $this->getCountryDataHelper()->haveCountry();
            $seed['fk_country'] = $countryTransfer->getIdCountry();
        }

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
     * @return \Spryker\Zed\CompanyUnitAddress\Business\CompanyUnitAddressFacadeInterface
     */
    protected function getCompanyUnitAddressFacade()
    {
        return $this->getLocator()->companyUnitAddress()->facade();
    }

    /**
     * @return \Codeception\Module|\SprykerTest\Zed\Country\Helper\CountryDataHelper
     */
    protected function getCountryDataHelper(): CountryDataHelper
    {
        return $this->getModule('\\' . CountryDataHelper::class);
    }
}
