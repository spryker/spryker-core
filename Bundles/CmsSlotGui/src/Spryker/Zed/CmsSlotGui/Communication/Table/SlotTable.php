<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotGui\Communication\Table;

use Orm\Zed\CmsSlot\Persistence\Map\SpyCmsSlotTableMap;
use Orm\Zed\CmsSlot\Persistence\SpyCmsSlotQuery;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsSlotGui\Communication\Controller\ActivateSlotController;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class SlotTable extends AbstractTable
{
    public const TABLE_CLASS = 'js-cms-slot-list-table';

    protected const COL_KEY = SpyCmsSlotTableMap::COL_KEY;
    protected const COL_NAME = SpyCmsSlotTableMap::COL_NAME;
    protected const COL_DESCRIPTION = SpyCmsSlotTableMap::COL_DESCRIPTION;
    protected const COL_OWNERSHIP = SpyCmsSlotTableMap::COL_CONTENT_PROVIDER_TYPE;
    protected const COL_STATUS = SpyCmsSlotTableMap::COL_IS_ACTIVE;
    protected const COL_ACTIONS = 'actions';

    protected const VALUE_COL_KEY = 'Slot Key';
    protected const VALUE_COL_NAME = 'Name';
    protected const VALUE_COL_DESCRIPTION = 'Description';
    protected const VALUE_COL_OWNERSHIP = 'Ownership';
    protected const VALUE_COL_STATUS = 'Status';
    protected const VALUE_COL_ACTIONS = 'Actions';

    protected const URL_ACTIVATE_BUTTON = '/cms-slot-gui/activate-slot/activate';
    protected const URL_DEACTIVATE_BUTTON = '/cms-slot-gui/activate-slot/deactivate';

    /**
     * @var int|null
     */
    protected $contentProviderTypesNumber = null;

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
        $this->tableClass = static::TABLE_CLASS;

        $config = $this->setHeader($config);
        $config = $this->setSortable($config);
        $config = $this->setSearchable($config);

        $config->setDefaultSortField(static::COL_KEY, TableConfiguration::SORT_ASC);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(static::COL_STATUS);

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
            static::COL_KEY => static::VALUE_COL_KEY,
            static::COL_NAME => static::VALUE_COL_NAME,
            static::COL_DESCRIPTION => static::VALUE_COL_DESCRIPTION,
            static::COL_OWNERSHIP => static::VALUE_COL_OWNERSHIP,
            static::COL_STATUS => static::VALUE_COL_STATUS,
            static::COL_ACTIONS => static::VALUE_COL_ACTIONS,
        ];

        if (!$this->isOwnershipColumnVisible()) {
            unset($header[static::COL_OWNERSHIP]);
        }

        $config->setHeader($header);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setSortable(TableConfiguration $config): TableConfiguration
    {
        $sortable = [
            static::COL_KEY,
            static::COL_NAME,
            static::COL_STATUS,
        ];

        if ($this->isOwnershipColumnVisible()) {
            $sortable[] = static::COL_OWNERSHIP;
        }

        $config->setSortable($sortable);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function setSearchable(TableConfiguration $config): TableConfiguration
    {
        $searchable = [
            static::COL_KEY,
            static::COL_NAME,
            static::COL_DESCRIPTION,
        ];

        if ($this->isOwnershipColumnVisible()) {
            $searchable[] = static::COL_OWNERSHIP;
        }

        $config->setSearchable($searchable);

        return $config;
    }

    /**
     * @return bool
     */
    protected function isOwnershipColumnVisible(): bool
    {
        if ($this->contentProviderTypesNumber === null) {
            $this->contentProviderTypesNumber = $this->cmsSlotQuery
                ->select(static::COL_OWNERSHIP)
                ->distinct()
                ->count();
        }

        return $this->contentProviderTypesNumber > 1;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config): array
    {
        if (!$this->idCmsSlotTemplate) {
            return [];
        }

        $this->cmsSlotQuery
            ->clear()
            ->useSpyCmsSlotToCmsSlotTemplateQuery()
                ->filterByFkCmsSlotTemplate($this->idCmsSlotTemplate)
            ->endUse();

        $slotResults = $this->runQuery($this->cmsSlotQuery, $config);
        $results = [];

        foreach ($slotResults as $slot) {
            $results[] = [
                static::COL_KEY => $slot[SpyCmsSlotTableMap::COL_KEY],
                static::COL_NAME => $slot[SpyCmsSlotTableMap::COL_NAME],
                static::COL_DESCRIPTION => $slot[SpyCmsSlotTableMap::COL_DESCRIPTION],
                static::COL_OWNERSHIP => $slot[SpyCmsSlotTableMap::COL_CONTENT_PROVIDER_TYPE],
                static::COL_STATUS => $this->getStatus($slot),
                static::COL_ACTIONS => $this->buildLinks($slot),
            ];

            if (!$this->isOwnershipColumnVisible()) {
                unset($results[static::COL_OWNERSHIP]);
            }
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

        $statusToggleButton = $this->generateButton(
            $this->getUrlActivate($slot[SpyCmsSlotTableMap::COL_ID_CMS_SLOT]),
            'Activate',
            ['class' => 'btn-view js-slot-activation']
        );

        if ($slot[static::COL_STATUS]) {
            $statusToggleButton = $this->generateButton(
                $this->getUrlDeactivate($slot[SpyCmsSlotTableMap::COL_ID_CMS_SLOT]),
                'Deactivate',
                ['class' => 'btn-danger js-slot-activation']
            );
        }

        $buttons[] = $statusToggleButton;

        return implode(' ', $buttons);
    }

    /**
     * @param array $slot
     *
     * @return string
     */
    protected function getStatus(array $slot): string
    {
        if ($slot[SpyCmsSlotTableMap::COL_IS_ACTIVE]) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Inactive', 'label-danger');
    }

    /**
     * @param int $idCmsSlot
     *
     * @return string
     */
    protected function getUrlActivate(int $idCmsSlot): string
    {
        return Url::generate(static::URL_ACTIVATE_BUTTON, [ActivateSlotController::PARAM_ID_CMS_SLOT => $idCmsSlot])->build();
    }

    /**
     * @param int $idCmsSlot
     *
     * @return string
     */
    protected function getUrlDeactivate(int $idCmsSlot): string
    {
        return Url::generate(static::URL_DEACTIVATE_BUTTON, [ActivateSlotController::PARAM_ID_CMS_SLOT => $idCmsSlot])->build();
    }
}
