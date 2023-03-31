<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Table;

use Orm\Zed\Country\Persistence\Map\SpyCountryStoreTableMap;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Orm\Zed\Country\Persistence\SpyCountryQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailableCountryStoreTable extends CountryStoreTable
{
    /**
     * @var string
     */
    protected $tableIdentifier = 'available-country-table';

    /**
     * @var \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed>
     */
    protected $countryQuery;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CountryGui\Communication\Expander\CountryStoreTableExpanderInterface> $expanders
     * @param \Orm\Zed\Country\Persistence\SpyCountryQuery<mixed> $countryQuery
     */
    public function __construct(?int $idStore, array $expanders, SpyCountryQuery $countryQuery)
    {
        parent::__construct($idStore, $expanders);
        $this->countryQuery = $countryQuery;
    }

    /**
     * @return string
     */
    protected function getBaseUrlPath(): string
    {
        return 'available-country-table';
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string>>
     */
    protected function getRawData(TableConfiguration $config): array
    {
        $query = $this->countryQuery;

        if ($this->idStore) {
            $query->addJoin(
                [SpyCountryTableMap::COL_ID_COUNTRY, $this->idStore],
                [SpyCountryStoreTableMap::COL_FK_COUNTRY, SpyCountryStoreTableMap::COL_FK_STORE],
                Criteria::LEFT_JOIN,
            )
                ->addAnd(
                    SpyCountryStoreTableMap::COL_FK_STORE,
                    null,
                    Criteria::ISNULL,
                );
        }

        $query = $query
            ->withColumn(SpyCountryTableMap::COL_NAME, static::COLUMN_NAME)
            ->withColumn(SpyCountryTableMap::COL_ISO2_CODE, static::COLUMN_ISO2_CODE)
            ->withColumn(SpyCountryTableMap::COL_ISO3_CODE, static::COLUMN_ISO3_CODE);

        $result = [];

        foreach ($this->runQuery($query, $config) as $item) {
            $result[] = (array)$item;
        }

        return $result;
    }
}
