<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Country;

use Generated\Zed\Ide\AutoCompletion;
use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\AbstractFunctionalTest;
use Spryker\Zed\Kernel\Locator;
use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Country\Persistence\CountryQueryContainer;
use Spryker\Zed\Country\Persistence\CountryQueryContainerInterface;
use Orm\Zed\Country\Persistence\SpyCountry;

/**
 * @group Country
 */
class CountryFacadeTest extends AbstractFunctionalTest
{

    const ISO2_CODE = 'qx';

    /**
     * @var Locator|AutoCompletion
     */
    protected $locator;

    /**
     * @var CountryFacade
     */
    protected $countryFacade;

    /**
     * @var CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

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
     * @return MessengerInterface
     */
    protected function getMockLogger()
    {
        return $this->getMock('Spryker\\Shared\\Kernel\\Messenger\\MessengerInterface');
    }

    /**
     * @return void
     */
    public function testInitdbInstallation()
    {
        $this->markTestSkipped('This test was using a mechanism to truncate tables, this is wrong in tests');

        $countryQuery = $this->countryQueryContainer->queryCountries();

        $countryCountBefore = $countryQuery->count();

        $this->countryFacade->install($this->getMockLogger());

        $countryCountAfter = $countryQuery->count();

        $this->assertTrue($countryCountAfter > $countryCountBefore);
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

}
