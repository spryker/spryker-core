<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Internal;

use Exception;
use Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface;
use Spryker\Zed\Country\Business\CountryManagerInterface;
use Spryker\Zed\Country\Business\RegionManagerInterface;
use Spryker\Zed\Country\CountryConfig;

class Install implements InstallInterface
{
    /**
     * @var \Spryker\Zed\Country\CountryConfig
     */
    protected $countrySettings;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface
     */
    protected $cldrDataProvider;

    /**
     * @var \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface
     */
    protected $codeMappingsProvider;

    /**
     * @var \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface
     */
    protected $postalCodeDataProvider;

    /**
     * @var \Spryker\Zed\Country\Business\CountryManagerInterface
     */
    protected $countryManager;

    /**
     * @var \Spryker\Zed\Country\Business\RegionManagerInterface
     */
    protected $regionManager;

    /**
     * @var array
     */
    protected $cldrData;

    /**
     * @var array
     */
    protected $codeMappings;

    /**
     * @var array
     */
    protected $postalCodes;

    /**
     * @param \Spryker\Zed\Country\Business\CountryManagerInterface $countryManager
     * @param \Spryker\Zed\Country\Business\RegionManagerInterface $regionManager
     * @param \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface $cldrDataProvider
     * @param \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface $codeMappingsProvider
     * @param \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface $postalCodeDataProvider
     * @param \Spryker\Zed\Country\CountryConfig $countrySettings
     */
    public function __construct(
        CountryManagerInterface $countryManager,
        RegionManagerInterface $regionManager,
        CldrDataProviderInterface $cldrDataProvider,
        CldrDataProviderInterface $codeMappingsProvider,
        CldrDataProviderInterface $postalCodeDataProvider,
        CountryConfig $countrySettings
    ) {
        //parent::__construct();
        $this->countrySettings = $countrySettings;
        $this->cldrDataProvider = $cldrDataProvider;
        $this->codeMappingsProvider = $codeMappingsProvider;
        $this->postalCodeDataProvider = $postalCodeDataProvider;
        $this->countryManager = $countryManager;
        $this->regionManager = $regionManager;
    }

    /**
     * @return void
     */
    public function install()
    {
        $this->init();
        $this->installCldrData();
        $this->installRegions();
    }

    /**
     * @return void
     */
    protected function init()
    {
        $this->cldrData = $this->cldrDataProvider->getCldrData();
        $this->version = $this->cldrData['main']['en']['identity']['version']['_cldrVersion'];
        $this->codeMappings = $this->codeMappingsProvider->getCldrData();
        $this->postalCodes = $this->postalCodeDataProvider->getCldrData();
    }

    /**
     * @return void
     */
    protected function installCldrData()
    {
        foreach ($this->getCountryList() as $iso2 => $countryData) {
            if (!$this->countryManager->hasCountry($iso2)) {
                $this->countryManager->createCountry($iso2, $countryData);
            }
        }
    }

    /**
     * @return array
     */
    protected function getCountryList()
    {
        $json = $this->cldrData;

        $countries = $json['main']['en']['localeDisplayNames']['territories'];
        $this->version = $json['main']['en']['identity']['version']['_cldrVersion'];

        $countries = array_filter(
            array_flip($countries),
            function ($value) {
                return (bool)preg_match('~^(?!' . implode('|', $this->countrySettings->getTerritoriesBlacklist()) . '$)[A-Z]{2}$~i', $value);
            }
        );

        $countries = array_flip($countries);

        $result = [];
        foreach ($countries as $iso2 => $country) {
            $result[$iso2]['name'] = $country;
            $result[$iso2]['iso2_code'] = $iso2;
        }

        $result = $this->addIso3Code($result);
        $result = $this->addPostalCodeData($result);

        return $result;
    }

    /**
     * @param array $countries
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function addIso3Code(array $countries)
    {
        $json = $this->codeMappings;

        if ($this->version !== $json['supplemental']['version']['_cldrVersion']) {
            throw new Exception('CLDR version mismatch in country install');
        }

        $mappings = $json['supplemental']['codeMappings'];

        foreach ($countries as $iso2 => $country) {
            if (isset($mappings[$iso2]['_alpha3'])) {
                $countries[$iso2]['iso3_code'] = $mappings[$iso2]['_alpha3'];
            } else {
                unset($countries[$iso2]);
            }
        }

        return $countries;
    }

    /**
     * @param array $countries
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function addPostalCodeData(array $countries)
    {
        $json = $this->postalCodes;

        if ($this->version !== $json['supplemental']['version']['_cldrVersion']) {
            throw new Exception('CLDR version mismatch in country install');
        }

        $mappings = $json['supplemental']['postalCodeData'];
        unset($json);

        foreach ($countries as $iso2 => $country) {
            if (!isset($mappings[$iso2])) {
                $countries[$iso2]['postal_code_mandatory'] = false;
                $countries[$iso2]['postal_code_regex'] = null;
            } else {
                // Let's check if a empty string is allowed
                if (preg_match('~' . $mappings[$iso2] . '~', '')) {
                    $countries[$iso2]['postal_code_mandatory'] = false;
                } else {
                    $countries[$iso2]['postal_code_mandatory'] = true;
                }
                $countries[$iso2]['postal_code_regex'] = $mappings[$iso2];
            }
        }

        return $countries;
    }

    /**
     * @return void
     */
    protected function installRegions()
    {
        foreach ($this->getCountriesToInstallRegionsFor() as $regionInstaller) {
            $fkCountry = $this->countryManager->getIdCountryByIso2Code($regionInstaller->getCountryIso());

            foreach ($regionInstaller->getCodeArray() as $isoCode => $regionName) {
                $this->regionManager->createRegion($isoCode, $fkCountry, $regionName);
            }
        }
    }

    /**
     * @return \Spryker\Zed\Country\Business\Internal\Regions\RegionInstallInterface[]
     */
    protected function getCountriesToInstallRegionsFor()
    {
        return [];
    }
}
