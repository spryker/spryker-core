<?php

/**
 * (c) Spryker Systems GmbH copyright protected
 */

namespace SprykerFeature\Zed\Country\Business;

use SprykerFeature\Zed\Country\Business\Exception\RegionExistsException;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;
use Orm\Zed\Country\Persistence\SpyRegion;

class RegionManager implements RegionManagerInterface
{

    /**
     * @var CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

    /**
     * @param CountryQueryContainerInterface $countryQueryContainer
     */
    public function __construct(
        CountryQueryContainerInterface $countryQueryContainer
    )
    {
        $this->countryQueryContainer = $countryQueryContainer;
    }

    /**
     * @param string $isoCode
     * @param int $fkCountry
     * @param string $regionName
     *
     * @return int
     */
    public function createRegion($isoCode, $fkCountry, $regionName)
    {
        $this->checkRegionDoesNotExist($isoCode);

        $region = new SpyRegion();
        $region
            ->setIso2Code($isoCode)
            ->setFkCountry($fkCountry)
            ->setName($regionName);

        $region->save();

        return $region->getIdRegion();
    }

    /**
     * @param string $isoCode
     *
     * @throws RegionExistsException
     */
    protected function checkRegionDoesNotExist($isoCode)
    {
        if ($this->hasRegion($isoCode)) {
            throw new RegionExistsException();
        }
    }

    /**
     * @param string $isoCode
     *
     * @return bool
     */
    public function hasRegion($isoCode)
    {
        $query = $this->countryQueryContainer->queryRegionByIsoCode($isoCode);

        return $query->count() > 0;
    }

}
