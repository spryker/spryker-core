<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Country\Business\Facade;

use Codeception\Test\Unit;
use Generated\Shared\Transfer\CountryConditionsTransfer;
use Generated\Shared\Transfer\CountryCriteriaTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Generated\Shared\Transfer\RegionTransfer;
use SprykerTest\Zed\Country\CountryBusinessTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Zed
 * @group Country
 * @group Business
 * @group Facade
 * @group GetCountryCollectionTest
 * Add your own group annotations below this line
 */
class GetCountryCollectionTest extends Unit
{
    /**
     * @var string
     */
    protected const COUNTRY_ISO2_CODE = '00';

    /**
     * @var string
     */
    protected const COUNTRY_ISO3_CODE = '000';

    /**
     * @var string
     */
    protected const COUNTRY_ISO2_CODE_SECOND = '01';

    /**
     * @var string
     */
    protected const COUNTRY_ISO3_CODE_SECOND = '001';

    /**
     * @var \SprykerTest\Zed\Country\CountryBusinessTester
     */
    protected CountryBusinessTester $tester;

    /**
     * @return void
     */
    public function testReturnsCountriesByIso2Codes(): void
    {
        // Arrange
        $this->tester->haveCountryTransfer([
            CountryTransfer::ISO2_CODE => static::COUNTRY_ISO2_CODE_SECOND,
            CountryTransfer::ISO3_CODE => static::COUNTRY_ISO3_CODE_SECOND,
        ]);
        $countryTransfer = $this->tester->haveCountryTransfer([
            CountryTransfer::ISO2_CODE => static::COUNTRY_ISO2_CODE,
            CountryTransfer::ISO3_CODE => static::COUNTRY_ISO3_CODE,
        ]);
        $this->tester->haveRegion([RegionTransfer::FK_COUNTRY => $countryTransfer->getIdCountry()]);
        $countryConditionsTransfer = (new CountryConditionsTransfer())->addIso2Code($countryTransfer->getIso2Code());
        $countryCriteriaTransfer = (new CountryCriteriaTransfer())->setCountryConditions($countryConditionsTransfer);

        // Act
        $countryCollectionTransfer = $this->tester->getFacade()->getCountryCollection($countryCriteriaTransfer);

        // Assert
        $this->assertCount(1, $countryCollectionTransfer->getCountries());
        /** @var \Generated\Shared\Transfer\CountryTransfer $retrievedCountryTransfer */
        $retrievedCountryTransfer = $countryCollectionTransfer->getCountries()->getIterator()->current();
        $this->assertSame($countryTransfer->getIso2Code(), $retrievedCountryTransfer->getIso2Code());
        $this->assertCount(0, $retrievedCountryTransfer->getRegions());
    }

    /**
     * @return void
     */
    public function testReturnsCountriesWithRegions(): void
    {
        // Arrange
        $countryTransfer = $this->tester->haveCountryTransfer([
            CountryTransfer::ISO2_CODE => static::COUNTRY_ISO2_CODE,
            CountryTransfer::ISO3_CODE => static::COUNTRY_ISO3_CODE,
        ]);
        $regionTransfer = $this->tester->haveRegion([RegionTransfer::FK_COUNTRY => $countryTransfer->getIdCountry()]);
        $countryConditionsTransfer = (new CountryConditionsTransfer())
            ->addIso2Code($countryTransfer->getIso2Code())
            ->setWithRegions(true);
        $countryCriteriaTransfer = (new CountryCriteriaTransfer())->setCountryConditions($countryConditionsTransfer);

        // Act
        $countryCollectionTransfer = $this->tester->getFacade()->getCountryCollection($countryCriteriaTransfer);

        // Assert
        $this->assertCount(1, $countryCollectionTransfer->getCountries());
        /** @var \Generated\Shared\Transfer\CountryTransfer $retrievedCountryTransfer */
        $retrievedCountryTransfer = $countryCollectionTransfer->getCountries()->getIterator()->current();
        $this->assertSame($countryTransfer->getIso2Code(), $retrievedCountryTransfer->getIso2Code());
        $this->assertCount(1, $retrievedCountryTransfer->getRegions());
        /** @var \Generated\Shared\Transfer\RegionTransfer $retrievedRegionTransfer */
        $retrievedRegionTransfer = $retrievedCountryTransfer->getRegions()->getIterator()->current();
        $this->assertSame($countryTransfer->getIdCountry(), $retrievedRegionTransfer->getFkCountry());
        $this->assertSame($regionTransfer->getName(), $retrievedRegionTransfer->getName());
    }
}
