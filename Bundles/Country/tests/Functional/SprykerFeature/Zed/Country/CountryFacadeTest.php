<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\SprykerFeature\Zed\Country;

use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\Messenger\MessengerInterface;
use SprykerEngine\Zed\Kernel\AbstractFunctionalTest;
use SprykerEngine\Zed\Kernel\Locator;
use Propel\Runtime\Propel;
use SprykerFeature\Zed\Country\Business\CountryFacade;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainer;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;
use SprykerFeature\Zed\Country\Persistence\Propel\SpyCountryQuery;

/**
 * @group Country
 */
class CountryFacadeTest extends AbstractFunctionalTest
{

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

    protected function setUp()
    {
        parent::setUp();
        $this->locator = Locator::getInstance();
        $this->countryFacade = $this->getFacade();
        $this->countryQueryContainer = new CountryQueryContainer(new \SprykerEngine\Zed\Kernel\Persistence\Factory('Country'), $this->locator);
    }

    protected function eraseCountries()
    {
        Propel::getConnection()->query('SET foreign_key_checks = 0;');
        SpyCountryQuery::create()->deleteAll();
        Propel::getConnection()->query('SET foreign_key_checks = 1;');
    }

    /**
     * @return MessengerInterface
     */
    protected function getMockLogger()
    {
        return $this->getMock('SprykerEngine\\Shared\\Kernel\\Messenger\\MessengerInterface');
    }

    public function testInitdbInstallation()
    {
        $this->eraseCountries();
        $countryQuery = $this->countryQueryContainer->queryCountries();

        $countryCountBefore = $countryQuery->count();

        $this->countryFacade->install($this->getMockLogger());

        $countryCountAfter = $countryQuery->count();

        $this->assertTrue($countryCountAfter > $countryCountBefore);
    }

    public function testGetIdByIso2CodeReturnsRightValue()
    {
        $country = $this->locator->country()->entitySpyCountry();
        $country->setIso2Code('qx');

        $country->save();

        $this->assertEquals($country->getIdCountry(), $this->countryFacade->getIdCountryByIso2Code('qx'));
    }

}
