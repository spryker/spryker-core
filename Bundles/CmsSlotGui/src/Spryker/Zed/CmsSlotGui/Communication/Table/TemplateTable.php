<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Table;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;
use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotToCmsSlotTemplateTableMap;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class TemplateTable extends AbstractTable
{
    protected const COL_ID = SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE;
    protected const COL_NAME = SpyCmsSlotTemplateTableMap::COL_NAME;
    protected const COL_DESCRIPTION = SpyCmsSlotTemplateTableMap::COL_DESCRIPTION;
    protected const COL_SLOTS_NUMBER = 'slotsNumber';

    protected const VALUE_COL_ID = 'ID';
    protected const VALUE_COL_NAME = 'Name';
    protected const VALUE_COL_DESCRIPTION = 'Description';
    protected const VALUE_COL_SLOTS_NUMBER = 'Number of Slots';

    protected const TABLE_IDENTIFIER = 'template-list-table';

    /**
     * @var \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery
     */
    protected $cmsSlotTemplateQuery;

    /**
     * @param \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery $cmsSlotTemplateQuery
     */
    public function __construct(SpyCmsSlotTemplateQuery $cmsSlotTemplateQuery)
    {
        $this->cmsSlotTemplateQuery = $cmsSlotTemplateQuery;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $config = $this->setHeader($config);

        $config->setSortable([
            static::COL_ID,
            static::COL_NAME,
        ]);

        $config->setDefaultSortField(static::COL_ID, TableConfiguration::SORT_ASC);

        $config->setSearchable([
            static::COL_ID,
            static::COL_NAME,
            static::COL_DESCRIPTION,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setHeader(TableConfiguration $config): TableConfiguration
    {
        $header = [
            static::COL_ID => static::VALUE_COL_ID,
            static::COL_NAME => static::VALUE_COL_NAME,
            static::COL_DESCRIPTION => static::VALUE_COL_DESCRIPTION,
            static::COL_SLOTS_NUMBER => static::VALUE_COL_SLOTS_NUMBER,
        ];

        $config->setHeader($header);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        $this->filterTemplatesWithoutSlots();
        $this->addSlotsNumber();

        $templateResults = $this->runQuery($this->cmsSlotTemplateQuery, $config);
        $results = [];

        foreach ($templateResults as $template) {
            $results[] = [
                static::COL_ID => $template[SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE],
                static::COL_NAME => $template[SpyCmsSlotTemplateTableMap::COL_NAME],
                static::COL_DESCRIPTION => $template[SpyCmsSlotTemplateTableMap::COL_DESCRIPTION],
                static::COL_SLOTS_NUMBER => $template[static::COL_SLOTS_NUMBER],
            ];
        }

        return $results;
    }

    /**
     * @return void
     */
    protected function filterTemplatesWithoutSlots(): void
    {
        $this->cmsSlotTemplateQuery
            ->innerJoinSpyCmsSlotToCmsSlotTemplate()
            ->groupByIdCmsSlotTemplate();
    }

    /**
     * @return void
     */
    protected function addSlotsNumber(): void
    {
        $this->cmsSlotTemplateQuery
            ->withColumn(
                'COUNT(' . SpyCmsSlotToCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TO_CMS_SLOT_TEMPLATE . ')',
                static::COL_SLOTS_NUMBER
            );
    }
}
