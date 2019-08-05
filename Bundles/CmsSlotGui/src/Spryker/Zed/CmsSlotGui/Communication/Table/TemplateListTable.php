<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Table;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTemplateTableMap;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotTemplateQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class TemplateListTable extends AbstractTable
{
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
            TemplateListConstants::COL_ID,
            TemplateListConstants::COL_NAME,
        ]);

        $config->setDefaultSortField(TemplateListConstants::COL_ID, TableConfiguration::SORT_ASC);

        $config->setSearchable([
            TemplateListConstants::COL_ID,
            TemplateListConstants::COL_NAME,
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
            TemplateListConstants::COL_ID => 'ID',
            TemplateListConstants::COL_NAME => 'Name',
            TemplateListConstants::COL_DESCRIPTION => 'Description',
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
        $templates = $this->runQuery($this->cmsSlotTemplateQuery, $config);
        $results = [];

        foreach ($templates as $key => $template) {
            $results[] = [
                TemplateListConstants::COL_ID => $template[SpyCmsSlotTemplateTableMap::COL_ID_CMS_SLOT_TEMPLATE],
                TemplateListConstants::COL_NAME => $template[SpyCmsSlotTemplateTableMap::COL_NAME],
                TemplateListConstants::COL_DESCRIPTION => $template[SpyCmsSlotTemplateTableMap::COL_DESCRIPTION],
            ];
        }

        return $results;
    }
}
