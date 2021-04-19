<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress;

use Orm\Zed\Country\Persistence\Map\SpyRegionTableMap;
use Orm\Zed\Country\Persistence\SpyRegionQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Exception\InvalidDataException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\StockAddressDataImport\Business\Writer\StockAddress\DataSet\StockAddressDataSetInterface;

class RegionNameToIdRegionStep implements DataImportStepInterface
{
    /**
     * @var array int|null>
     */
    protected static $idRegionCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     * @throws \Spryker\Zed\DataImport\Business\Exception\InvalidDataException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet)
    {
        $iso2Code = $dataSet[StockAddressDataSetInterface::COLUMN_COUNTRY_ISO2_CODE];
        $regionName = $dataSet[StockAddressDataSetInterface::COLUMN_REGION_NAME] ?? null;

        if (!$iso2Code) {
            throw new InvalidDataException(sprintf('ISO2 code is missing.'));
        }

        if (!$regionName) {
            $dataSet[StockAddressDataSetInterface::ID_REGION] = null;

            return;
        }

        $regionEntity = SpyRegionQuery::create()
            ->filterByName($regionName)
            ->findOne();

        if (!$regionEntity) {
            throw new EntityNotFoundException(sprintf('Region "%s" not found.', $regionName));
        }

        $dataSet[StockAddressDataSetInterface::ID_REGION] = $regionEntity->getIdRegion();
    }

    /**
     * @param string $regionName
     * @param string $iso2Code
     *
     * @return int|null
     */
    protected function findIdRegionByRegionNameAndIsoCode(string $regionName, string $iso2Code): ?int
    {
        if (array_key_exists($regionName, static::$idRegionCache)) {
            return static::$idRegionCache[$regionName];
        }

        /** @var int|null $idRegion */
        $idRegion = SpyRegionQuery::create()
            ->filterByName($regionName)
            ->filterByIso2Code($iso2Code)
            ->select(SpyRegionTableMap::COL_ID_REGION)
            ->findOne();

        static::$idRegionCache[$regionName] = $idRegion;

        return static::$idRegionCache[$regionName];
    }
}
