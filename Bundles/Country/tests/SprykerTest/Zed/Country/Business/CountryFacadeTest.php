<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Country\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CountryCollectionBuilder;
use Generated\Shared\Transfer\CountryTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyRegion;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Country\Business\Exception\MissingCountryException;
use Spryker\Zed\Country\Persistence\CountryQueryContainer;

/**
 * Auto-generated group annotations
 * @group SprykerTest
 * @group Zed
 * @group Country
 * @group Business
 * @group Facade
 * @group CountryFacadeTest
 * Add your own group annotations below this line
 */
class CountryFacadeTest extends Unit
{
    public const ISO2_CODE = 'qx';
    public const ISO3_CODE = 'qxz';

    /**
     * @var \Spryker\Zed\Country\Business\CountryFacade
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

    /**
     * @var \SprykerTest\Zed\Country\CountryBusinessTester
     */
    protected $tester;

    /**
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        $this->countryFacade = new CountryFacade();
        $this->countryQueryContainer = new CountryQueryContainer();
    }

    /**
     * @return \Psr\Log\LoggerInterface
     */
    protected function getMockLogger()
    {
        return $this->getMockBuilder(LoggerInterface::class)->getMock();
    }

    /**
     * @return void
     */
    public function testGetIdByIso2CodeReturnsRightValue()
    {
        $country = new SpyCountry();
        $country->setIso2Code(self::ISO2_CODE);

        $country->save();

        $this->assertEquals($country->getIdCountry(), $this->countryFacade->getIdCountryByIso2Code(self::ISO2_CODE));
    }

    /**
     * @return void
     */
    public function testGetCountryByIso2CodeReturnsRightValue()
    {
        $country = new SpyCountry();
        $country->setIso2Code(self::ISO2_CODE);
        $country->setIso3Code(self::ISO3_CODE);

        $country->save();

        $result = $this->countryFacade->getCountryByIso2Code(self::ISO2_CODE);

        $this->assertInstanceOf(CountryTransfer::class, $result);
        $this->assertEquals($country->getIdCountry(), $result->getIdCountry());
    }

    /**
     * @return void
     */
    public function testGetCountryByIso3CodeReturnsRightValue()
    {
        $country = new SpyCountry();
        $country->setIso2Code(self::ISO2_CODE);
        $country->setIso3Code(self::ISO3_CODE);

        $country->save();

        $result = $this->countryFacade->getCountryByIso3Code(self::ISO3_CODE);

        $this->assertInstanceOf(CountryTransfer::class, $result);
        $this->assertEquals($country->getIdCountry(), $result->getIdCountry());
    }

    /**
     * @return void
     */
    public function testGetCountryByIso3CodeReturnsException()
    {
        $this->expectException(MissingCountryException::class);
        $this->countryFacade->getCountryByIso3Code(self::ISO3_CODE);
    }

    /**
     * @return void
     */
    public function testGetCountryByIso2CodeReturnsException()
    {
        $this->expectException(MissingCountryException::class);
        $this->countryFacade->getCountryByIso2Code(self::ISO2_CODE);
    }

    /**
     * @return void
     */
    public function testGetCountriesByCountryIso2CodesReturnsRightValue()
    {
        $country = new SpyCountry();
        $country->setIso2Code(self::ISO2_CODE);
        $country->save();

        $region = new SpyRegion();
        $region->setName('test');
        $region->setFkCountry($country->getIdCountry());
        $region->setIso2Code('TS');
        $region->save();

        $countryCollectionTransfer = (new CountryCollectionBuilder())->build()->addCountries(
            (new CountryTransfer())->setIso2Code($country->getIso2Code())
        );

        $countryTransfer = $this->countryFacade->findCountriesByIso2Codes($countryCollectionTransfer);

        $this->assertEquals('TS', $countryTransfer->getCountries()[0]->getRegions()[0]->getIso2Code());
    }
}
