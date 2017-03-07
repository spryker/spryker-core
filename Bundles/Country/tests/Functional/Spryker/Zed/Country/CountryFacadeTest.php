<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Functional\Spryker\Zed\Country;

use Codeception\TestCase\Test;
use Orm\Zed\Country\Persistence\SpyCountry;
use Psr\Log\LoggerInterface;
use Spryker\Zed\Country\Business\CountryFacade;
use Spryker\Zed\Country\Persistence\CountryQueryContainer;

/**
 * @group Functional
 * @group Spryker
 * @group Zed
 * @group Country
 * @group CountryFacadeTest
 */
class CountryFacadeTest extends Test
{

    const ISO2_CODE = 'qx';

    /**
     * @var \Spryker\Zed\Country\Business\CountryFacade
     */
    protected $countryFacade;

    /**
     * @var \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface
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

}
