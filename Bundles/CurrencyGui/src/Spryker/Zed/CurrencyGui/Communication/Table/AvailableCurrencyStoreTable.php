<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Table;

use Orm\Zed\Currency\Persistence\Map\SpyCurrencyStoreTableMap;
use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailableCurrencyStoreTable extends CurrencyStoreTable
{
    /**
     * @var string
     */
    protected $tableIdentifier = 'available-currency-table';

    /**
     * @var \Orm\Zed\Currency\Persistence\SpyCurrencyQuery<mixed>
     */
    protected $currencyQuery;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CurrencyGui\Communication\Expander\CurrencyStoreTableExpanderInterface> $expanders
     * @param \Orm\Zed\Currency\Persistence\SpyCurrencyQuery<mixed> $currencyQuery
     */
    public function __construct(?int $idStore, array $expanders, SpyCurrencyQuery $currencyQuery)
    {
        parent::__construct($idStore, $expanders);
        $this->currencyQuery = $currencyQuery;
    }

    /**
     * @return string
     */
    protected function getBaseUrlPath(): string
    {
        return 'available-currency-table';
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string>>
     */
    protected function getRawData(TableConfiguration $config): array
    {
        $query = $this->currencyQuery;

        if ($this->idStore) {
            $query->addJoin(
                [SpyCurrencyTableMap::COL_ID_CURRENCY, $this->idStore],
                [SpyCurrencyStoreTableMap::COL_FK_CURRENCY, SpyCurrencyStoreTableMap::COL_FK_STORE],
                Criteria::LEFT_JOIN,
            )
                ->addAnd(
                    SpyCurrencyStoreTableMap::COL_FK_STORE,
                    null,
                    Criteria::ISNULL,
                );
        }

        $query = $query
            ->withColumn(static::ENTITY_COLUMN_NAME, static::COLUMN_NAME)
            ->withColumn(static::ENTITY_COLUMN_CODE, static::COLUMN_CODE);

        $result = [];

        foreach ($this->runQuery($query, $config) as $item) {
            $result[] = (array)$item;
        }

        return $result;
    }
}
