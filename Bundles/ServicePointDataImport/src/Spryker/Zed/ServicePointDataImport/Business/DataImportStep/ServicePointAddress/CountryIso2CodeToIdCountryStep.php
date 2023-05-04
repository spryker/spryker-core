<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress;

use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointAddressDataSetInterface;

class CountryIso2CodeToIdCountryStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $countryIdsIndexedByCountryIso2Code = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $countryIso2Code */
        $countryIso2Code = $dataSet[ServicePointAddressDataSetInterface::COLUMN_COUNTRY_ISO2_CODE];

        if (!$countryIso2Code) {
            throw new InvalidDataException(
                sprintf('"%s" is required.', ServicePointAddressDataSetInterface::COLUMN_COUNTRY_ISO2_CODE),
            );
        }

        if (!isset($this->countryIdsIndexedByCountryIso2Code[$countryIso2Code])) {
            $this->countryIdsIndexedByCountryIso2Code[$countryIso2Code] = $this->getIdCountryByIso2Code($countryIso2Code);
        }

        $dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_COUNTRY] = $this->countryIdsIndexedByCountryIso2Code[$countryIso2Code];
    }

    /**
     * @param string $countryIso2Code
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return int
     */
    protected function getIdCountryByIso2Code(string $countryIso2Code): int
    {
        /** @var int $idCountry */
        $idCountry = $this->getCountryQuery()
            ->select(SpyCountryTableMap::COL_ID_COUNTRY)
            ->findOneByIso2Code($countryIso2Code);

        if (!$idCountry) {
            throw new EntityNotFoundException(
                sprintf('Could not find Country by iso2 code "%s".', $countryIso2Code),
            );
        }

        return $idCountry;
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyCountryQuery
     */
    protected function getCountryQuery(): SpyCountryQuery
    {
        return SpyCountryQuery::create();
    }
}
