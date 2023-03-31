<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Table;

use Orm\Zed\Locale\Persistence\Map\SpyLocaleStoreTableMap;
use Orm\Zed\Locale\Persistence\Map\SpyLocaleTableMap;
use Orm\Zed\Locale\Persistence\SpyLocaleQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AvailableLocaleStoreTable extends LocaleStoreTable
{
    /**
     * @var string
     */
    protected $tableIdentifier = 'available-locale-table';

    /**
     * @var \Orm\Zed\Locale\Persistence\SpyLocaleQuery<mixed>
     */
    protected $localeQuery;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\LocaleGui\Communication\Expander\LocaleStoreTableExpanderInterface> $expanders
     * @param \Orm\Zed\Locale\Persistence\SpyLocaleQuery<mixed> $localeQuery
     */
    public function __construct(?int $idStore, array $expanders, SpyLocaleQuery $localeQuery)
    {
        parent::__construct($idStore, $expanders);
        $this->localeQuery = $localeQuery;
    }

    /**
     * @return string
     */
    protected function getBaseUrlPath(): string
    {
        return 'available-locale-table';
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<array<string>>
     */
    protected function getRawData(TableConfiguration $config): array
    {
        $query = $this->localeQuery;

        if ($this->idStore) {
            $query->addJoin(
                [SpyLocaleTableMap::COL_ID_LOCALE, $this->idStore],
                [SpyLocaleStoreTableMap::COL_FK_LOCALE, SpyLocaleStoreTableMap::COL_FK_STORE],
                Criteria::LEFT_JOIN,
            )
                ->addAnd(
                    SpyLocaleStoreTableMap::COL_FK_STORE,
                    null,
                    Criteria::ISNULL,
                );
        }

        $query = $query
            ->withColumn(static::ENTITY_COLUMN_NAME, static::COLUMN_NAME);

        $result = [];

        foreach ($this->runQuery($query, $config) as $item) {
            $result[] = (array)$item;
        }

        return $result;
    }
}
