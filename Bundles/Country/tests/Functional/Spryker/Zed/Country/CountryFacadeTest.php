<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace Functional\Spryker\Zed\Country;

use Spryker\Shared\Kernel\Messenger\MessengerInterface;
use Spryker\Zed\Kernel\AbstractFunctionalTest;
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
        return $this->getMock(MessengerInterface::class);
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
