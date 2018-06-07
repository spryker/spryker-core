<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\CompanyUnitAddress\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CompanyUnitAddressBuilder;
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

        $this->getCompanyUnitAddressFacade()->create($companyUnitAddressTransfer);

        return $companyUnitAddressTransfer;
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
