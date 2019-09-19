<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Zed\Country\Business;

use Codeception\Test\Unit;
use Generated\Shared\DataBuilder\CheckoutDataBuilder;
use Generated\Shared\DataBuilder\CountryCollectionBuilder;
use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CheckoutDataTransfer;
use Generated\Shared\Transfer\CountryTransfer;
use Orm\Zed\Country\Persistence\SpyCountry;
use Orm\Zed\Country\Persistence\SpyRegion;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Country\Business\Exception\MissingCountryException;
use Spryker\Zed\Country\Persistence\CountryQueryContainer;

/**
 * Auto-generated group annotations
 *
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

    protected const ISO2_COUNTRY_DE = 'DE';

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

    /**
     * @return void
     */
    public function testCountryFacadeWillValidateCountryCheckoutWithoutErrors(): void
    {
        $checkoutDataTransfer = $this->prepareCheckoutDataTransferWithIso2Codes();
        $checkoutResponseTransfer = $this->countryFacade->validateCountryCheckoutData($checkoutDataTransfer);

        $this->assertTrue($checkoutResponseTransfer->getIsSuccess());
        $this->assertEquals(0, $checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return void
     */
    public function testCountryFacadeWillValidateCountryCheckoutWithErrors(): void
    {
        $checkoutDataTransfer = $this->prepareCheckoutDataTransferWithOutIso2Codes();
        $checkoutResponseTransfer = $this->countryFacade->validateCountryCheckoutData($checkoutDataTransfer);

        $this->assertFalse($checkoutResponseTransfer->getIsSuccess());
        $this->assertGreaterThan(0, $checkoutResponseTransfer->getErrors()->count());
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    protected function prepareCheckoutDataTransferWithIso2Codes(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withBillingAddress(['billingAddress' => (new AddressTransfer())->setIso2Code(static::ISO2_COUNTRY_DE)])
            ->withShippingAddress(['shippingAddress' => (new AddressTransfer())->setIso2Code(static::ISO2_COUNTRY_DE)])
            ->build();

        return $checkoutDataTransfer;
    }

    /**
     * @return \Generated\Shared\Transfer\CheckoutDataTransfer
     */
    protected function prepareCheckoutDataTransferWithOutIso2Codes(): CheckoutDataTransfer
    {
        /** @var \Generated\Shared\Transfer\CheckoutDataTransfer $checkoutDataTransfer */
        $checkoutDataTransfer = (new CheckoutDataBuilder())
            ->withBillingAddress(['billingAddress' => new AddressTransfer()])
            ->withShippingAddress(['shippingAddress' => new AddressTransfer()])
            ->build();

        return $checkoutDataTransfer;
    }
}
