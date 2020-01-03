<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business;

use Spryker\Zed\Country\Business\Cldr\JsonFileCldrDataProvider;
use Spryker\Zed\Country\Business\Country\CountryReader;
use Spryker\Zed\Country\Business\Country\CountryReaderInterface;
use Spryker\Zed\Country\Business\Internal\Install;
use Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidator;
use Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidatorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Country\Persistence\CountryQueryContainerInterface getQueryContainer()
 */
class CountryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Country\Business\Internal\Install
     */
    public function createInstaller()
    {
        $installer = new Install(
            $this->createCountryManager(),
            $this->createRegionManager(),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/en/territories.json'
            ),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/supplemental/codeMappings.json'
            ),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/supplemental/postalCodeData.json'
            ),
            $this->getConfig()
        );

        return $installer;
    }

    /**
     * @return \Spryker\Zed\Country\Business\CountryManagerInterface
     */
    public function createCountryManager()
    {
        return new CountryManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\RegionManagerInterface
     */
    protected function createRegionManager()
    {
        return new RegionManager(
            $this->getQueryContainer()
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Country\CountryReaderInterface
     */
    public function createCountryReader(): CountryReaderInterface
    {
        return new CountryReader(
            $this->getRepository()
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidatorInterface
     */
    public function createCountryCheckoutDataValidator(): CountryCheckoutDataValidatorInterface
    {
        return new CountryCheckoutDataValidator($this->createCountryManager());
    }

    /**
     * @param string $filePath
     *
     * @return \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface
     */
    protected function createCldrDataProvider($filePath)
    {
        return new JsonFileCldrDataProvider(
            $filePath
        );
    }
}
