<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CountryDataImport\Business\DataImportStep;

use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Spryker\Zed\CountryDataImport\Business\DataSet\CountryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CountryIso2CodeToIdCountryStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed>
     */
    protected $countryQuery;

    /**
     * @var array<int>
     */
    protected static $idCountryCache = [];

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed> $countryQuery
     */
    public function __construct(SpyCountryQuery $countryQuery)
    {
        $this->countryQuery = $countryQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $countryIso2Code = $dataSet[CountryStoreDataSetInterface::COLUMN_COUNTRY_NAME];

        if (!isset(static::$idCountryCache[$countryIso2Code])) {
            $countryEntity = $this->countryQuery
                ->clear()
                ->filterByIso2Code($countryIso2Code)
                ->findOne();

            if ($countryEntity === null) {
                throw new EntityNotFoundException(sprintf('Country not found: %s', $countryIso2Code));
            }

            static::$idCountryCache[$countryIso2Code] = $countryEntity->getIdCountry();
        }

        $dataSet[CountryStoreDataSetInterface::ID_COUNTRY] = static::$idCountryCache[$countryIso2Code];
    }
}
