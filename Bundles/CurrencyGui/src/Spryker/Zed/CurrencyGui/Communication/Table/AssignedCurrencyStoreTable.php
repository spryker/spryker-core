<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CurrencyGui\Communication\Table;

use Orm\Zed\Currency\Persistence\Base\SpyCurrencyStoreQuery;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedCurrencyStoreTable extends CurrencyStoreTable
{
    /**
     * @var string
     */
    protected $tableIdentifier = 'assigned-currency-table';

    /**
     * @var \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery<mixed>
     */
    protected $currencyStoreQuery;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\CurrencyGui\Communication\Expander\CurrencyStoreTableExpanderInterface> $expanders
     * @param \Orm\Zed\Currency\Persistence\SpyCurrencyStoreQuery<mixed> $currencyStoreQuery
     */
    public function __construct(?int $idStore, array $expanders, SpyCurrencyStoreQuery $currencyStoreQuery)
    {
        parent::__construct($idStore, $expanders);
        $this->currencyStoreQuery = $currencyStoreQuery;
    }

    /**
     * @return string
     */
    protected function getBaseUrlPath(): string
    {
        return 'assigned-currency-table';
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

        $query = $this->currencyStoreQuery
            ->innerJoinWithCurrency()
            ->filterByFkStore($this->idStore)
            ->withColumn(static::ENTITY_COLUMN_NAME, static::COLUMN_NAME)
            ->withColumn(static::ENTITY_COLUMN_CODE, static::COLUMN_CODE);

        $result = [];

        foreach ($this->runQuery($query, $config) as $item) {
            $result[] = (array)$item;
        }

        return $result;
    }
}
