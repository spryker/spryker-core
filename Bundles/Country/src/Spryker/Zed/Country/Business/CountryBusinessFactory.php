<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business;

use Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface;
use Spryker\Zed\Country\Business\Cldr\JsonFileCldrDataProvider;
use Spryker\Zed\Country\Business\Country\CountryReader;
use Spryker\Zed\Country\Business\Country\CountryReaderInterface;
use Spryker\Zed\Country\Business\Country\CountryWriter;
use Spryker\Zed\Country\Business\Country\CountryWriterInterface;
use Spryker\Zed\Country\Business\Expander\RegionExpander;
use Spryker\Zed\Country\Business\Expander\RegionExpanderInterface;
use Spryker\Zed\Country\Business\Expander\StoreExpander;
use Spryker\Zed\Country\Business\Expander\StoreExpanderInterface;
use Spryker\Zed\Country\Business\Internal\Install;
use Spryker\Zed\Country\Business\Internal\InstallInterface;
use Spryker\Zed\Country\Business\Region\RegionReader;
use Spryker\Zed\Country\Business\Region\RegionReaderInterface;
use Spryker\Zed\Country\Business\Region\RegionWriter;
use Spryker\Zed\Country\Business\Region\RegionWriterInterface;
use Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidator;
use Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidatorInterface;
use Spryker\Zed\Kernel\Business\AbstractBusinessFactory;

/**
 * @method \Spryker\Zed\Country\CountryConfig getConfig()
 * @method \Spryker\Zed\Country\Persistence\CountryRepositoryInterface getRepository()
 * @method \Spryker\Zed\Country\Persistence\CountryEntityManagerInterface getEntityManager()
 */
class CountryBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Spryker\Zed\Country\Business\Internal\InstallInterface
     */
    public function createInstaller(): InstallInterface
    {
        return new Install(
            $this->createCountryReader(),
            $this->createRegionReader(),
            $this->createCountryWriter(),
            $this->createRegionWriter(),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/en/territories.json',
            ),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/supplemental/codeMappings.json',
            ),
            $this->createCldrDataProvider(
                $this->getConfig()->getCldrDir() . '/supplemental/postalCodeData.json',
            ),
            $this->getConfig(),
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Country\CountryReaderInterface
     */
    public function createCountryReader(): CountryReaderInterface
    {
        return new CountryReader(
            $this->getRepository(),
            $this->createRegionExpander(),
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Expander\RegionExpanderInterface
     */
    public function createRegionExpander(): RegionExpanderInterface
    {
        return new RegionExpander($this->getRepository());
    }

    /**
     * @return \Spryker\Zed\Country\Business\Country\CountryWriterInterface
     */
    public function createCountryWriter(): CountryWriterInterface
    {
        return new CountryWriter(
            $this->getEntityManager(),
            $this->createCountryReader(),
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Region\RegionReaderInterface
     */
    public function createRegionReader(): RegionReaderInterface
    {
        return new RegionReader(
            $this->getRepository(),
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Region\RegionWriterInterface
     */
    public function createRegionWriter(): RegionWriterInterface
    {
        return new RegionWriter(
            $this->getEntityManager(),
            $this->createRegionReader(),
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Validator\CountryCheckoutDataValidatorInterface
     */
    public function createCountryCheckoutDataValidator(): CountryCheckoutDataValidatorInterface
    {
        return new CountryCheckoutDataValidator(
            $this->createCountryReader(),
            $this->getRepository(),
        );
    }

    /**
     * @param string $filePath
     *
     * @return \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface
     */
    public function createCldrDataProvider(string $filePath): CldrDataProviderInterface
    {
        return new JsonFileCldrDataProvider(
            $filePath,
        );
    }

    /**
     * @return \Spryker\Zed\Country\Business\Expander\StoreExpanderInterface
     */
    public function createStoreExpander(): StoreExpanderInterface
    {
        return new StoreExpander($this->getRepository());
    }
}
