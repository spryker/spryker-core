<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\CountryDataImport\Business\DataImportStep;

use Orm\Zed\Country\Persistence\SpyCountryStoreQuery;
use Spryker\Zed\CountryDataImport\Business\DataSet\CountryStoreDataSetInterface;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;

class CountryStoreWriterStep implements DataImportStepInterface
{
    /**
     * @var \Orm\Zed\Country\Persistence\SpyCountryStoreQuery<mixed>
     */
    protected $countryStoreQuery;

    /**
     * @param \Orm\Zed\Country\Persistence\SpyCountryStoreQuery<mixed> $countryStoreQuery
     */
    public function __construct(SpyCountryStoreQuery $countryStoreQuery)
    {
        $this->countryStoreQuery = $countryStoreQuery;
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface<string> $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $this->countryStoreQuery
            ->clear()
            ->filterByFkCountry($dataSet[CountryStoreDataSetInterface::ID_COUNTRY])
            ->filterByFkStore($dataSet[CountryStoreDataSetInterface::ID_STORE])
            ->findOneOrCreate()
            ->save();
    }
}
