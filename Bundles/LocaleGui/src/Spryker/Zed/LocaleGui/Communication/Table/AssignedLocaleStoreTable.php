<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\LocaleGui\Communication\Table;

use Orm\Zed\Locale\Persistence\Base\SpyLocaleStoreQuery;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedLocaleStoreTable extends LocaleStoreTable
{
    /**
     * @var string
     */
    protected $tableIdentifier = 'assigned-locale-table';

    /**
     * @var \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery<mixed>
     */
    protected $localeStoreQuery;

    /**
     * @param int|null $idStore
     * @param array<\Spryker\Zed\LocaleGui\Communication\Expander\LocaleStoreTableExpanderInterface> $expanders
     * @param \Orm\Zed\Locale\Persistence\SpyLocaleStoreQuery<mixed> $localeStoreQuery
     */
    public function __construct(?int $idStore, array $expanders, SpyLocaleStoreQuery $localeStoreQuery)
    {
        parent::__construct($idStore, $expanders);
        $this->localeStoreQuery = $localeStoreQuery;
    }

    /**
     * @return string
     */
    protected function getBaseUrlPath(): string
    {
        return 'assigned-locale-table';
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

        $query = $this->localeStoreQuery
            ->innerJoinWithLocale()
            ->filterByFkStore($this->idStore)
            ->withColumn(static::ENTITY_COLUMN_NAME, static::COLUMN_NAME);

        $result = [];

        foreach ($this->runQuery($query, $config) as $item) {
            $result[] = (array)$item;
        }

        return $result;
    }
}
