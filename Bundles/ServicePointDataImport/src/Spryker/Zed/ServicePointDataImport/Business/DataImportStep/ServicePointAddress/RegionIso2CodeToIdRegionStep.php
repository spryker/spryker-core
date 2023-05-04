<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\ServicePointDataImport\Business\DataImportStep\ServicePointAddress;

use Orm\Zed\Country\Persistence\Map\SpyRegionTableMap;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\ServicePointDataImport\Business\DataSet\ServicePointAddressDataSetInterface;

class RegionIso2CodeToIdRegionStep implements DataImportStepInterface
{
    /**
     * @var array<string, int>
     */
    protected array $regionIdsIndexedByRegionIso2Code = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        /** @var string $regionIso2Code */
        $regionIso2Code = $dataSet[ServicePointAddressDataSetInterface::COLUMN_REGION_ISO2_CODE];

        if (!$regionIso2Code) {
            return;
        }

        if (!isset($this->regionIdsIndexedByRegionIso2Code[$regionIso2Code])) {
            $this->regionIdsIndexedByRegionIso2Code[$regionIso2Code] = $this->getIdRegionByRegionIso2Code($regionIso2Code);
        }

        $dataSet[ServicePointAddressDataSetInterface::COLUMN_ID_REGION] = $this->regionIdsIndexedByRegionIso2Code[$regionIso2Code];
    }

    /**
     * @param string $regionIso2Code
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return int
     */
    protected function getIdRegionByRegionIso2Code(string $regionIso2Code): int
    {
        /** @var int|null $idRegion */
        $idRegion = $this->getRegionQuery()
            ->select(SpyRegionTableMap::COL_ID_REGION)
            ->findOneByIso2Code($regionIso2Code);

        if (!$idRegion) {
            throw new InvalidDataException(
                sprintf('Could not find Region by iso2 code "%s".', ServicePointAddressDataSetInterface::COLUMN_REGION_ISO2_CODE),
            );
        }

        return $idRegion;
    }

    /**
     * @return \Orm\Zed\Country\Persistence\SpyRegionQuery
     */
    protected function getRegionQuery(): SpyRegionQuery
    {
        return SpyRegionQuery::create();
    }
}
