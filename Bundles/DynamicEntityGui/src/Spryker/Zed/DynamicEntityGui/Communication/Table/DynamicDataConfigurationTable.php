<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\DynamicEntityGui\Communication\Table;

use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration;
use Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\DynamicEntityGui\DynamicEntityGuiConfig;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class DynamicDataConfigurationTable extends AbstractTable
{
    /**
     * @var string
     */
    protected const COL_ID_DYNAMIC_ENTITY = 'id_dynamic_entity_configuration';

    /**
     * @var string
     */
    protected const COL_TABLE_NAME = 'table_name';

    /**
     * @var string
     */
    protected const COL_TABLE_ALIAS = 'table_alias';

    /**
     * @var string
     */
    protected const COL_IS_ACTIVE = 'is_active';

    /**
     * @var string
     */
    protected const TABLE_COL_ACTION = 'Actions';

    /**
     * @var string
     */
    protected const VALUE_COL_ID = 'ID';

    /**
     * @var string
     */
    protected const VALUE_COL_TABLE_NAME = 'Table name';

    /**
     * @var string
     */
    protected const VALUE_COL_RESOURCE_NAME = 'Resource name';

    /**
     * @var string
     */
    protected const VALUE_COL_IS_ACTIVE = 'Status';

    /**
     * @var string
     */
    protected const EDIT_BUTTON = 'Edit';

    /**
     * @var string
     */
    protected const URL_PARAM_TABLE_NAME = 'table-name';

    /**
     * @var \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery
     */
    protected SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationQuery;

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationQuery
     */
    public function __construct(SpyDynamicEntityConfigurationQuery $dynamicEntityConfigurationQuery)
    {
        $this->dynamicEntityConfigurationQuery = $dynamicEntityConfigurationQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setHeader($config);
        $this->setSearchable($config);
        $this->setRawColumns($config);
        $config->setDefaultSortField(static::COL_ID_DYNAMIC_ENTITY, TableConfiguration::SORT_DESC);

        $this->setTableIdentifier(static::COL_ID_DYNAMIC_ENTITY);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setHeader(TableConfiguration $config): void
    {
        $config->setHeader([
            static::COL_ID_DYNAMIC_ENTITY => static::VALUE_COL_ID,
            static::COL_TABLE_NAME => static::VALUE_COL_TABLE_NAME,
            static::COL_TABLE_ALIAS => static::VALUE_COL_RESOURCE_NAME,
            static::COL_IS_ACTIVE => static::VALUE_COL_IS_ACTIVE,
            static::TABLE_COL_ACTION => static::TABLE_COL_ACTION,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setSearchable(TableConfiguration $config): void
    {
        $config->setSearchable([
            static::COL_TABLE_NAME,
            static::COL_TABLE_ALIAS,
            static::COL_IS_ACTIVE,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function setRawColumns(TableConfiguration $config): void
    {
        $config->setRawColumns([
            static::COL_IS_ACTIVE,
            static::TABLE_COL_ACTION,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array<int, array<string, mixed>>
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $dynamicEntitiesCollection = [];

        $this->prepareQuery();
        $queryResults = $this->runQuery($this->dynamicEntityConfigurationQuery, $config, true);

        /** @var \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $dynamicEntityConfigurationEntity */
        foreach ($queryResults as $dynamicEntityConfigurationEntity) {
            $dynamicEntitiesCollection[] = $this->generateItem($dynamicEntityConfigurationEntity);
        }

        return $dynamicEntitiesCollection;
    }

    /**
     * @param \Orm\Zed\DynamicEntity\Persistence\SpyDynamicEntityConfiguration $dynamicEntityConfigurationEntity
     *
     * @return array<string, mixed>
     */
    protected function generateItem(SpyDynamicEntityConfiguration $dynamicEntityConfigurationEntity): array
    {
        return [
            static::COL_ID_DYNAMIC_ENTITY => $this->formatInt($dynamicEntityConfigurationEntity->getIdDynamicEntityConfiguration()),
            static::COL_TABLE_NAME => $dynamicEntityConfigurationEntity->getTableName(),
            static::COL_TABLE_ALIAS => $dynamicEntityConfigurationEntity->getTableAlias(),
            static::COL_IS_ACTIVE => $this->getStatusLabel((bool)$dynamicEntityConfigurationEntity->getIsActive()),
            static::TABLE_COL_ACTION => $this->generateEditButton(
                $this->createEditUrl($dynamicEntityConfigurationEntity->getTableName()),
                static::EDIT_BUTTON,
            ),
        ];
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    protected function getStatusLabel(bool $isActive): string
    {
        if (!$isActive) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-success');
    }

    /**
     * @return void
     */
    protected function prepareQuery(): void
    {
        $this->dynamicEntityConfigurationQuery
            ->orderBy(static::COL_IS_ACTIVE, TableConfiguration::SORT_DESC)
            ->orderBy(static::COL_TABLE_NAME, TableConfiguration::SORT_ASC);
    }

    /**
     * @param string $tableName
     *
     * @return string
     */
    protected function createEditUrl(string $tableName): string
    {
        return Url::generate(
            DynamicEntityGuiConfig::URL_DYNAMIC_DATA_CONFIGURATION_EDIT,
            [
                static::URL_PARAM_TABLE_NAME => $tableName,
            ],
        );
    }
}
