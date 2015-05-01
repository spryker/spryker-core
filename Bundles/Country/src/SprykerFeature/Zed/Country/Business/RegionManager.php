<?php


namespace SprykerFeature\Zed\Country\Business;


use Generated\Zed\Ide\AutoCompletion;
use SprykerEngine\Shared\Kernel\LocatorLocatorInterface;
use SprykerFeature\Zed\Country\Business\Exception\RegionExistsException;
use SprykerFeature\Zed\Country\Persistence\CountryQueryContainerInterface;

class RegionManager implements RegionManagerInterface
{
    /**
     * @var CountryQueryContainerInterface
     */
    protected $countryQueryContainer;

    /**
     * @var AutoCompletion
     */
    protected $locator;

    /**
     * @param CountryQueryContainerInterface $countryQueryContainer
     * @param LocatorLocatorInterface $locator
     */
    public function __construct(
        CountryQueryContainerInterface $countryQueryContainer,
        LocatorLocatorInterface $locator
    ) {
        $this->countryQueryContainer = $countryQueryContainer;
        $this->locator = $locator;
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

        $region = $this->locator->country()->entitySpyRegion();
        $region
            ->setIso2Code($isoCode)
            ->setFkCountry($fkCountry)
            ->setName($regionName)
        ;

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
