<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CmsBlockGui\Communication\Table;

use Orm\Zed\CmsBlock\Persistence\Map\SpyCmsBlockTableMap;
use Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery;
use Propel\Runtime\Collection\ObjectCollection;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class CmsBlockTable extends AbstractTable
{
    public const COL_ID_CMS_BLOCK = SpyCmsBlockTableMap::COL_ID_CMS_BLOCK;
    public const COL_NAME = SpyCmsBlockTableMap::COL_NAME;
    public const COL_ACTIONS = 'Actions';
    public const COL_IS_ACTIVE = SpyCmsBlockTableMap::COL_IS_ACTIVE;
    public const COL_STORE_RELATION = 'Store';
    public const COL_TEMPLATE_NAME = 'template_name';

    public const REQUEST_ID_CMS_BLOCK = 'id-cms-block';

    public const URL_CMS_BLOCK_GLOSSARY = '/cms-block-gui/edit-glossary';
    public const URL_CMS_BLOCK_VIEW = '/cms-block-gui/view-block';
    public const URL_CMS_BLOCK_EDIT = '/cms-block-gui/edit-block';
    public const URL_CMS_BLOCK_DEACTIVATE = '/cms-block-gui/edit-block/deactivate';
    public const URL_CMS_BLOCK_ACTIVATE = '/cms-block-gui/edit-block/activate';

    /**
     * @var \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery
     */
    protected $cmsBlockQuery;

    /**
     * @var \Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface
     */
    protected $cmsBlockQueryContainer;

    /**
     * @param \Orm\Zed\CmsBlock\Persistence\SpyCmsBlockQuery $cmsBlockQuery
     * @param \Spryker\Zed\CmsBlockGui\Dependency\QueryContainer\CmsBlockGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer
     */
    public function __construct(SpyCmsBlockQuery $cmsBlockQuery, CmsBlockGuiToCmsBlockQueryContainerInterface $cmsBlockQueryContainer)
    {
        $this->cmsBlockQuery = $cmsBlockQuery;
        $this->cmsBlockQueryContainer = $cmsBlockQueryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_CMS_BLOCK => 'Block Id',
            static::COL_NAME => 'Name',
            static::COL_TEMPLATE_NAME => 'Template',
            static::COL_IS_ACTIVE => 'Status',
            static::COL_STORE_RELATION => 'Stores',
            static::COL_ACTIONS => static::COL_ACTIONS,
        ]);

        $config->addRawColumn(static::COL_ACTIONS);
        $config->addRawColumn(static::COL_IS_ACTIVE);
        $config->addRawColumn(static::COL_STORE_RELATION);

        $config->setSortable([
            static::COL_ID_CMS_BLOCK,
            static::COL_NAME,
            static::COL_TEMPLATE_NAME,
            static::COL_IS_ACTIVE,
        ]);

        $config->setDefaultSortDirection(TableConfiguration::SORT_DESC);

        $config->setSearchable([
            static::COL_ID_CMS_BLOCK,
            static::COL_TEMPLATE_NAME,
            static::COL_NAME,
            static::COL_NAME,
        ]);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $queryResults = $this->runQuery($this->cmsBlockQuery, $config);
        $results = [];

        foreach ($queryResults as $item) {
            $results[] = [
                static::COL_ID_CMS_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
                static::COL_NAME => $item[SpyCmsBlockTableMap::COL_NAME],
                static::COL_TEMPLATE_NAME => $item[static::COL_TEMPLATE_NAME],
                static::COL_IS_ACTIVE => $this->generateStatusLabels($item),
                static::COL_STORE_RELATION => $this->getStoreNames($item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK]),
                static::COL_ACTIONS => $this->buildLinks($item),
            ];
        }
        unset($queryResults);

        return $results;
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function buildLinks(array $item)
    {
        $buttons = [];

        $buttons[] = $this->generateViewButton(
            Url::generate(static::URL_CMS_BLOCK_VIEW, [
                static::REQUEST_ID_CMS_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
            ]),
            'View Block'
        );

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_CMS_BLOCK_GLOSSARY, [
                static::REQUEST_ID_CMS_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
            ]),
            'Edit Placeholder'
        );

        $buttons[] = $this->generateEditButton(
            Url::generate(static::URL_CMS_BLOCK_EDIT, [
                static::REQUEST_ID_CMS_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
            ]),
            'Edit Block'
        );

        $buttons[] = $this->generateStatusChangeButton($item);

        return implode(' ', $buttons);
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function generateStatusChangeButton(array $item)
    {
        if ($item[SpyCmsBlockTableMap::COL_IS_ACTIVE]) {
            return $this->generateRemoveButton(
                Url::generate(static::URL_CMS_BLOCK_DEACTIVATE, [
                    static::REQUEST_ID_CMS_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
                ]),
                'Deactivate'
            );
        } else {
            return $this->generateViewButton(
                Url::generate(static::URL_CMS_BLOCK_ACTIVATE, [
                        static::REQUEST_ID_CMS_BLOCK => $item[SpyCmsBlockTableMap::COL_ID_CMS_BLOCK],
                    ]),
                'Activate'
            );
        }
    }

    /**
     * @param array $item
     *
     * @return string
     */
    protected function generateStatusLabels(array $item)
    {
        if ($item[SpyCmsBlockTableMap::COL_IS_ACTIVE]) {
            return $this->generateLabel('Active', 'label-info');
        }

        return $this->generateLabel('Inactive', 'label-danger');
    }

    /**
     * @param int $idCmsBlock
     *
     * @return string
     */
    protected function getStoreNames($idCmsBlock)
    {
        $cmsBlockStoreCollection = $this
            ->cmsBlockQueryContainer
            ->queryCmsBlockStoreWithStoreByFkCmsBlock($idCmsBlock)
            ->find();

        return $this->formatStoreNames($cmsBlockStoreCollection);
    }

    /**
     * @param \Propel\Runtime\Collection\ObjectCollection $cmsBlockStoreEntityCollection
     *
     * @return string
     */
    protected function formatStoreNames(ObjectCollection $cmsBlockStoreEntityCollection)
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
}
