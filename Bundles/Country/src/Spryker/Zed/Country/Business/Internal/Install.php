<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Country\Business\Internal;

use Exception;
use Generated\Shared\Transfer\CountryTransfer;
use Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface;
use Spryker\Zed\Country\Business\Country\CountryReaderInterface;
use Spryker\Zed\Country\Business\Country\CountryWriterInterface;
use Spryker\Zed\Country\Business\Region\RegionReaderInterface;
use Spryker\Zed\Country\Business\Region\RegionWriterInterface;
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
     * @var \Spryker\Zed\Country\Business\Country\CountryReaderInterface
     */
    protected $countryReader;

    /**
     * @var \Spryker\Zed\Country\Business\Region\RegionReaderInterface
     */
    protected $regionReader;

    /**
     * @var \Spryker\Zed\Country\Business\Country\CountryWriterInterface
     */
    protected $countryWriter;

    /**
     * @var \Spryker\Zed\Country\Business\Region\RegionWriterInterface
     */
    protected $regionWriter;

    /**
     * @var array<mixed>
     */
    protected $cldrData;

    /**
     * @var array<mixed>
     */
    protected $codeMappings;

    /**
     * @var array<mixed>
     */
    protected $postalCodes;

    /**
     * @param \Spryker\Zed\Country\Business\Country\CountryReaderInterface $countryReader
     * @param \Spryker\Zed\Country\Business\Region\RegionReaderInterface $regionReader
     * @param \Spryker\Zed\Country\Business\Country\CountryWriterInterface $countryWriter
     * @param \Spryker\Zed\Country\Business\Region\RegionWriterInterface $regionWriter
     * @param \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface $cldrDataProvider
     * @param \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface $codeMappingsProvider
     * @param \Spryker\Zed\Country\Business\Cldr\CldrDataProviderInterface $postalCodeDataProvider
     * @param \Spryker\Zed\Country\CountryConfig $countrySettings
     */
    public function __construct(
        CountryReaderInterface $countryReader,
        RegionReaderInterface $regionReader,
        CountryWriterInterface $countryWriter,
        RegionWriterInterface $regionWriter,
        CldrDataProviderInterface $cldrDataProvider,
        CldrDataProviderInterface $codeMappingsProvider,
        CldrDataProviderInterface $postalCodeDataProvider,
        CountryConfig $countrySettings
    ) {
        $this->countrySettings = $countrySettings;
        $this->cldrDataProvider = $cldrDataProvider;
        $this->codeMappingsProvider = $codeMappingsProvider;
        $this->postalCodeDataProvider = $postalCodeDataProvider;
        $this->countryReader = $countryReader;
        $this->regionReader = $regionReader;
        $this->countryWriter = $countryWriter;
        $this->regionWriter = $regionWriter;
    }

    /**
     * @return void
     */
    public function install(): void
    {
        $this->init();
        $this->installCldrData();
        $this->installRegions();
    }

    /**
     * @return void
     */
    protected function init(): void
    {
        $this->cldrData = $this->cldrDataProvider->getCldrData();
        $this->version = $this->cldrData['main']['en']['identity']['version']['_cldrVersion'];
        $this->codeMappings = $this->codeMappingsProvider->getCldrData();
        $this->postalCodes = $this->postalCodeDataProvider->getCldrData();
    }

    /**
     * @return void
     */
    protected function installCldrData(): void
    {
        foreach ($this->getCountryList() as $iso2 => $countryData) {
            if ($this->countryReader->countryExists($iso2)) {
                continue;
            }

            $countryTransfer = (new CountryTransfer())
                ->setIso2Code($iso2)
                ->setIso3Code($countryData['iso3_code'])
                ->setName($countryData['name'])
                ->setPostalCodeMandatory($countryData['postal_code_mandatory'])
                ->setPostalCodeRegex($countryData['postal_code_regex']);

            $this->countryWriter->createCountry($countryTransfer);
        }
    }

    /**
     * @return array<mixed>
     */
    protected function getCountryList(): array
    {
        $json = $this->cldrData;

        $countries = $json['main']['en']['localeDisplayNames']['territories'];
        $this->version = $json['main']['en']['identity']['version']['_cldrVersion'];

        $countries = array_filter(
            array_flip($countries),
            function ($value) {
                return (bool)preg_match('~^(?!' . implode('|', $this->countrySettings->getTerritoriesBlacklist()) . '$)[A-Z]{2}$~i', $value);
            },
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
     * @param array<mixed> $countries
     *
     * @throws \Exception
     *
     * @return array<mixed>
     */
    protected function addIso3Code(array $countries): array
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
     * @param array<mixed> $countries
     *
     * @throws \Exception
     *
     * @return array<mixed>
     */
    protected function addPostalCodeData(array $countries): array
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
    protected function installRegions(): void
    {
        foreach ($this->getCountriesToInstallRegionsFor() as $regionInstaller) {
            $fkCountry = $this->countryReader->getCountryByIso2Code($regionInstaller->getCountryIso())
                ->getIdCountryOrFail();

            foreach ($regionInstaller->getCodeArray() as $isoCode => $regionName) {
                $this->regionWriter->createRegion($isoCode, $fkCountry, $regionName);
            }
        }
    }

    /**
     * @return array<\Spryker\Zed\Country\Business\Internal\Regions\RegionInstallInterface>
     */
    protected function getCountriesToInstallRegionsFor(): array
    {
        return [];
    }
}
