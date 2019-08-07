<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Table;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SlotListTable extends AbstractTable
{
    /**
     * @var \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery
     */
    protected $cmsSlotQuery;

    /**
     * @var int|null
     */
    protected $idCmsSlotTemplate;

    /**
     * @param \Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery $cmsSlotQuery
     * @param int|null $idCmsSlotTemplate
     */
    public function __construct(SpyCmsSlotQuery $cmsSlotQuery, ?int $idCmsSlotTemplate = null)
    {
        $this->cmsSlotQuery = $cmsSlotQuery;
        $this->idCmsSlotTemplate = $idCmsSlotTemplate;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->tableClass = SlotListConstants::TABLE_CLASS;

        $config = $this->setHeader($config);

        $config->setSortable([
            SlotListConstants::COL_KEY,
            SlotListConstants::COL_NAME,
            SlotListConstants::COL_OWNERSHIP,
            SlotListConstants::COL_STATUS,
        ]);

        $config->setDefaultSortField(SlotListConstants::COL_KEY, TableConfiguration::SORT_ASC);

        $config->setSearchable([
            SlotListConstants::COL_KEY,
            SlotListConstants::COL_NAME,
            SlotListConstants::COL_DESCRIPTION,
            SlotListConstants::COL_OWNERSHIP,
        ]);

        $config->addRawColumn(SlotListConstants::COL_ACTIONS);
        $config->addRawColumn(SlotListConstants::COL_STATUS);
        $config->setPageLength(3);

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
            SlotListConstants::COL_KEY => 'Slot key',
            SlotListConstants::COL_NAME => 'Name',
            SlotListConstants::COL_DESCRIPTION => 'Description',
            SlotListConstants::COL_OWNERSHIP => 'Ownership',
            SlotListConstants::COL_STATUS => 'Status',
            SlotListConstants::COL_ACTIONS => 'Actions',
        ];

        $config->setHeader($header);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        if (!$this->idCmsSlotTemplate) {
            return [];
        }

        $this->cmsSlotQuery
            ->useSpyCmsSlotToCmsSlotTemplateQuery()
                ->filterByFkCmsSlotTemplate($this->idCmsSlotTemplate)
            ->endUse();

        $slots = $this->runQuery($this->cmsSlotQuery, $config);
        $results = [];

        foreach ($slots as $key => $slot) {
            $results[] = [
                SlotListConstants::COL_KEY => $slot[SpyCmsSlotTableMap::COL_KEY],
                SlotListConstants::COL_NAME => $slot[SpyCmsSlotTableMap::COL_NAME],
                SlotListConstants::COL_DESCRIPTION => $slot[SpyCmsSlotTableMap::COL_DESCRIPTION],
                SlotListConstants::COL_OWNERSHIP => $slot[SpyCmsSlotTableMap::COL_CONTENT_PROVIDER_TYPE],
                SlotListConstants::COL_STATUS => $this->getStatus($slot),
                SlotListConstants::COL_ACTIONS => $this->buildLinks($slot),
            ];
        }

        return $results;
    }

    /**
     * @param array $slot
     *
     * @return string
     */
    protected function buildLinks(array $slot): string
    {
        $buttons[] = $this->generateEditButton(
            '#',
            'Edit'
        );

        $activateButton = $this->generateButton(
            '/cms-slot-gui/activate-slot/activate?id-cms-slot=' . $slot[SpyCmsSlotTableMap::COL_ID_CMS_SLOT],
            'Activate',
            ['class' => 'btn-view slot-activation']
        );

        if ($slot[SlotListConstants::COL_STATUS]) {
            $activateButton = $this->generateButton(
                '/cms-slot-gui/activate-slot/deactivate?id-cms-slot=' . $slot[SpyCmsSlotTableMap::COL_ID_CMS_SLOT],
                'Deactivate',
                ['class' => 'btn-danger slot-activation']
            );
        }

        $buttons[] = $activateButton;

        return implode(' ', $buttons);
    }

    /**
     * @param array $slot
     *
     * @return string
     */
    protected function getStatus(array $slot)
    {
        if ($slot[SpyCmsSlotTableMap::COL_IS_ACTIVE]) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Inactive', 'label-danger');
    }
}
