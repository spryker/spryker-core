<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress;

use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointAddressDataSetInterface;

class RegionBelongsToCountryStep implements DataImportStepInterface
{
    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        if (empty($dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_REGION]) || $this->isRegionBelongsToCountry($dataSet)) {
            return;
        }

        throw new InvalidDataException(sprintf(
            'Region with iso2 code "%s" does not belong to country with iso2 code "%s"',
            $dataSet[ServicePointAddressDataSetInterface::COLUMN_REGION_ISO2_CODE],
            $dataSet[ServicePointAddressDataSetInterface::COLUMN_COUNTRY_ISO2_CODE],
        ));
    }

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return bool
     */
    protected function isRegionBelongsToCountry(DataSetInterface $dataSet): bool
    {
        return $this->getRegionQuery()
            ->filterByIdRegion($dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_REGION])
            ->filterByFkCountry($dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_COUNTRY])
            ->exists();
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    protected function getRegionQuery(): SpyRegionQuery
    {
        return SpyRegionQuery::create();
    }
}
