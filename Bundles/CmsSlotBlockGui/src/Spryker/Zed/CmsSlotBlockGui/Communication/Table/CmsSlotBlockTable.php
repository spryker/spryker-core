<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsSlotBlockGui\Communication\Table;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Orm\Zed\CmsSlotBlock\Persistence\SpyCmsSlotBlockQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsSlotBlockTable extends AbstractTable
{
    public const COL_CMS_BLOCK = 'CmsBlock';
    public const COL_ID_CMS_BLOCK = SpyCmsBlockTableMap::COL_ID_CMS_BLOCK;
    public const COL_NAME = SpyCmsBlockTableMap::COL_NAME;
    public const COL_VALID_FROM = SpyCmsBlockTableMap::COL_VALID_FROM;
    public const COL_VALID_TO = SpyCmsBlockTableMap::COL_VALID_TO;
    public const COL_IS_ACTIVE = SpyCmsBlockTableMap::COL_IS_ACTIVE;
    public const COL_STORE_RELATION = 'Store';
    public const COL_ACTIONS = 'Actions';

    /**
     * @var \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    protected $cmsBlockQuery;

    /**
     * @var int
     */
    protected $idCmsSlot;

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery $cmsBlockQuery
     * @param int $idCmsSlot
     */
    public function __construct(SpyCmsBlockQuery $cmsBlockQuery, int $idCmsSlot)
    {
        $this->cmsBlockQuery = $cmsBlockQuery;
        $this->idCmsSlot = $idCmsSlot;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = $this->setHeader($config);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(static::COL_IS_ACTIVE);
        $config->addRawColumn(static::COL_STORE_RELATION);

        $this->disableSearch();

        $config->setServerSide(false);
        $config->setPaging(false);
        $config->setOrdering(false);

        //todo
        $this->setLimit(1000);

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
            static::COL_ID_CMS_BLOCK => 'ID',
            static::COL_NAME => 'Name',
            static::COL_VALID_FROM => 'Valid From',
            static::COL_VALID_TO => 'Valid To',
            static::COL_IS_ACTIVE => 'Status',
            static::COL_STORE_RELATION => 'Stores',
            static::COL_ACTIONS => static::COL_ACTIONS,
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
        if (!$this->idCmsSlot) {
            return [];
        }

        $this->cmsBlockQuery
            ->clear()
            ->useSpyCmsSlotBlockQuery()
                ->filterByFkCmsSlot($this->idCmsSlot)
            ->endUse();

        $cmsBlockResults = $this->runQuery($this->cmsBlockQuery, $config);

        $results = [];
        foreach ($cmsBlockResults as $cmsBlock) {
            $results[] = [
                static::COL_ID_CMS_BLOCK => $cmsBlock[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
                static::COL_NAME => $cmsBlock[SpyCmsBlockTableMap::COL_NAME],
                static::COL_VALID_FROM => $cmsBlock[SpyCmsBlockTableMap::COL_VALID_FROM],
                static::COL_VALID_TO => $cmsBlock[SpyCmsBlockTableMap::COL_VALID_TO],
                static::COL_IS_ACTIVE => $this->generateStatusLabels($cmsBlock),
                static::COL_STORE_RELATION =>  $this->getStoreNames($cmsBlock[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK]),
                static::COL_STORE_RELATION => '',
                static::COL_ACTIONS => $this->getActionButtons(),
            ];
        }

        return $results;
    }

    /**
     * @param array $cmsSlotBlock
     *
     * @return string
     */
    protected function generateStatusLabels(array $cmsSlotBlock): string
    {
        if ($cmsSlotBlock[SpyCmsBlockTableMap::COL_IS_ACTIVE]) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Inactive', 'label-danger');
    }

    /**
     * @param int $idCmsBlock
     *
     * @return string
     */
    protected function getStoreNames(int $idCmsBlock): string
    {
        $cmsBlockStoreCollection = $this->cmsBlockQuery
            ->joinSpyCmsBlockStore()
                ->useSpyCmsBlockStoreQuery()
                    ->filterByFkCmsBlock_In([$idCmsBlock])
                    ->joinWithSpyStore()
                ->endUse()
            ->find();

        return $this->formatStoreNames($cmsBlockStoreCollection);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $cmsBlockStoreEntityCollection
     *
     * @return string
     */
    protected function formatStoreNames(ObjectCollection $cmsBlockStoreEntityCollection): string
    {
        $storeNames = [];
        foreach ($cmsBlockStoreEntityCollection as $cmsBlockStoreEntity) {
            $storeNames[] = sprintf(
                '<span class="label label-info">%s</span>',
                $cmsBlockStoreEntity->getSpyStore()->getName()
            );
        }

        return implode(" ", $storeNames);
    }

    /**
     * @param int $idProductAbstract
     *
     * @return string
     */
    protected function getActionButtons(): string
    {
        $actionButtons = [];

        $actionButtons[] = $this->generateButton(
            '#',
            'Move Up',
            [
                'class' => 'btn-create',
                'data-direction' => 'up',
                'icon' => 'fa-arrow-up',
                'onclick' => 'return false;',
            ]
        );
        $actionButtons[] = $this->generateButton(
            '#',
            'Move Down',
            [
                'class' => 'btn-create',
                'data-direction' => 'down',
                'icon' => 'fa-arrow-down',
                'onclick' => 'return false;',
            ]
        );
        $actionButtons[] = $this->generateButton(
            '#',
            'Delete',
            [
                'class' => 'btn-view',
                'onclick' => 'return false;',
            ]
        );
        $actionButtons[] = $this->generateButton(
            '#',
            'Delete',
            [
                'class' => 'btn-danger',
                'icon' => 'fa-trash',
                'onclick' => 'return false;',
            ]
        );

        return implode(' ', $actionButtons);
    }
}
