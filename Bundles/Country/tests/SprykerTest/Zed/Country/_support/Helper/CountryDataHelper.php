<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Country\Helper;

use Codeception\Module;
use Generated\Shared\DataBuilder\CountryBuilder;
use SprykerTest\Shared\Testify\Helper\LocatorHelperTrait;

class CountryDataHelper extends Module
{
    use LocatorHelperTrait;

    /**
     * @param array $seed
     *
     * @return \Generated\Shared\Transfer\CountryTransfer
     */
    public function haveCountry(array $seed = [])
    {
        $countryTransferBuilder = new CountryBuilder($seed);
        $countryTransfer = $countryTransferBuilder->build();

        return $this->getCountryFacade()->getCountryByIso2Code(
            $countryTransfer->getIso2Code()
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\CountryFacadeInterface
     */
    protected function getCountryFacade()
    {
        return $this->getLocator()->country()->facade();
    }
}
