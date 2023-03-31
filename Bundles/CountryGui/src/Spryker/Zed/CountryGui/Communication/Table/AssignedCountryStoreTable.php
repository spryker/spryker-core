<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CountryGui\Communication\Table;

use Orm\Zed\Country\Persistence\Base\SpyCountryStoreQuery;
use Orm\Zed\Country\Persistence\Map\SpyCountryTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedCountryStoreTable extends CountryStoreTable
{
    /**
     * @var string
     */
    protected $tableIdentifier = 'assigned-country-table';

    /**
     * @var \Orm\Zed\Country\Persistence\SpyCountryStoreQuery<mixed>
     */
    protected $countryStoreQuery;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CountryGui\Communication\Expander\CountryStoreTableExpanderInterface> $expanders
     * @param \Orm\Zed\Country\Persistence\SpyCountryStoreQuery<mixed> $countryStoreQuery
     */
    public function __construct(?int $idStore, array $expanders, SpyCountryStoreQuery $countryStoreQuery)
    {
        parent::__construct($idStore, $expanders);
        $this->countryStoreQuery = $countryStoreQuery;
    }

    /**
     * @return string
     */
    protected function getBaseUrlPath(): string
    {
        return 'assigned-country-table';
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string>>
     */
    protected function getRawData(TableConfiguration $config): array
    {
        if ($this->idStore === null) {
            return [];
        }

        $query = $this->countryStoreQuery
            ->innerJoinCountry()
            ->filterByFkStore($this->idStore)
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
