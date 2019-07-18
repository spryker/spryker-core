<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelProductAbstractTableMap;
use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Communication\Controller\EditController;
use Spryker\Zed\ProductLabelGui\Communication\Controller\SetStatusController;
use Spryker\Zed\ProductLabelGui\Communication\Controller\ViewController;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface;

class ProductLabelTable extends AbstractTable
{
    public const TABLE_IDENTIFIER = 'product-label-table';
    public const COL_ID_PRODUCT_LABEL = SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL;
    public const COL_POSITION = SpyProductLabelTableMap::COL_POSITION;
    public const COL_NAME = SpyProductLabelTableMap::COL_NAME;
    public const COL_IS_EXCLUSIVE = SpyProductLabelTableMap::COL_IS_EXCLUSIVE;
    public const COL_VALIDITY = 'validity';
    public const COL_ABSTRACT_PRODUCT_RELATION_COUNT = 'abstract_product_relation_count';
    public const COL_STATUS = SpyProductLabelTableMap::COL_IS_ACTIVE;
    public const COL_ACTIONS = 'actions';

    /**
     * @var \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface
     */
    protected $queryContainer;

    /**
     * @param \Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface $queryContainer
     */
    public function __construct(ProductLabelGuiQueryContainerInterface $queryContainer)
    {
        $this->queryContainer = $queryContainer;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config)
    {
        $this->setTableIdentifier(static::TABLE_IDENTIFIER);

        $this->configureHeader($config);
        $this->configureRawColumns($config);
        $this->configureSorting($config);
        $this->configureSearching($config);

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureHeader(TableConfiguration $config)
    {
        $config->setHeader([
            static::COL_ID_PRODUCT_LABEL => '#',
            static::COL_POSITION => 'Priority',
            static::COL_NAME => 'Name',
            static::COL_IS_EXCLUSIVE => 'Is Exclusive',
            static::COL_VALIDITY => 'Validity',
            static::COL_ABSTRACT_PRODUCT_RELATION_COUNT => 'Products Applied to',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureRawColumns(TableConfiguration $config)
    {
        $config->addRawColumn(static::COL_STATUS);
        $config->addRawColumn(static::COL_ACTIONS);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSorting(TableConfiguration $config)
    {
        $config->setDefaultSortField(
            static::COL_ID_PRODUCT_LABEL,
            TableConfiguration::SORT_ASC
        );

        $config->setSortable([
            static::COL_ID_PRODUCT_LABEL,
            static::COL_POSITION,
            static::COL_NAME,
            static::COL_IS_EXCLUSIVE,
            static::COL_STATUS,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return void
     */
    protected function configureSearching(TableConfiguration $config)
    {
        $config->setSearchable([
            static::COL_NAME,
        ]);
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->queryContainer->queryProductLabels();
        $this->addAbstractProductRelationCountToQuery($query);
        /** @var \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[] $productLabelEntities */
        $productLabelEntities = $this->runQuery($query, $config, true);

        $tableRows = [];

        foreach ($productLabelEntities as $productLabelEntity) {
            $tableRows[] = [
                static::COL_ID_PRODUCT_LABEL => $productLabelEntity->getIdProductLabel(),
                static::COL_POSITION => $productLabelEntity->getPosition(),
                static::COL_NAME => $productLabelEntity->getName(),
                static::COL_IS_EXCLUSIVE => $this->getIsExclusiveLabel($productLabelEntity),
                static::COL_VALIDITY => $this->getValidityDateRangeLabel($productLabelEntity),
                static::COL_ABSTRACT_PRODUCT_RELATION_COUNT => $productLabelEntity->getVirtualColumn(static::COL_ABSTRACT_PRODUCT_RELATION_COUNT),
                static::COL_STATUS => $this->createStatusMarker($productLabelEntity->getIsActive()),
                static::COL_ACTIONS => $this->createActionButtons($productLabelEntity),
            ];
        }

        return $tableRows;
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabelQuery $query
     *
     * @return void
     */
    protected function addAbstractProductRelationCountToQuery(SpyProductLabelQuery $query)
    {
        $query
            ->useSpyProductLabelProductAbstractQuery(null, Criteria::LEFT_JOIN)
                ->withColumn(
                    sprintf('COUNT(%s)', SpyProductLabelProductAbstractTableMap::COL_FK_PRODUCT_ABSTRACT),
                    static::COL_ABSTRACT_PRODUCT_RELATION_COUNT
                )
                ->groupByFkProductLabel()
            ->endUse();
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return string
     */
    protected function getIsExclusiveLabel(SpyProductLabel $productLabelEntity)
    {
        return $productLabelEntity->getIsExclusive() ? 'Yes' : 'No';
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return string
     */
    protected function getValidityDateRangeLabel(SpyProductLabel $productLabelEntity)
    {
        if (!$productLabelEntity->getValidFrom() && !$productLabelEntity->getValidTo()) {
            return 'n/a';
        }

        return sprintf(
            '%s - %s',
            $productLabelEntity->getValidFrom('Y/m/d'),
            $productLabelEntity->getValidTo('Y/m/d')
        );
    }

    /**
     * @param bool $isActive
     *
     * @return string
     */
    protected function createStatusMarker($isActive)
    {
        $statusName = $isActive ? 'Active' : 'Inactive';
        $statusCssClass = $isActive ? 'label-info' : 'label-danger';

        return $this->generateLabel($statusName, $statusCssClass);
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return string
     */
    protected function createActionButtons(SpyProductLabel $productLabelEntity)
    {
        $idProductLabel = $productLabelEntity->getIdProductLabel();
        $actionButtons = [
            $this->createViewButton($idProductLabel),
            $this->createEditButton($idProductLabel),
            $this->createStatusToggleButton($idProductLabel, $productLabelEntity->getIsActive()),
        ];

        return implode(' ', $actionButtons);
    }

    /**
     * @param int $idProductLabel
     *
     * @return string
     */
    protected function createViewButton($idProductLabel)
    {
        return $this->generateViewButton(
            Url::generate(
                '/product-label-gui/view',
                [
                    ViewController::PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                ]
            ),
            'View'
        );
    }

    /**
     * @param int $idProductLabel
     *
     * @return string
     */
    protected function createEditButton($idProductLabel)
    {
        return $this->generateEditButton(
            Url::generate(
                '/product-label-gui/edit',
                [
                    EditController::PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                ]
            ),
            'Edit'
        );
    }

    /**
     * @param int $idProductLabel
     * @param bool $isActive
     *
     * @return string
     */
    protected function createStatusToggleButton($idProductLabel, $isActive)
    {
        if ($isActive) {
            return $this->createDeactivateButton($idProductLabel);
        }

        return $this->createActivateButton($idProductLabel);
    }

    /**
     * @param int $idProductLabel
     *
     * @return string
     */
    protected function createDeactivateButton($idProductLabel)
    {
        return $this->generateRemoveButton(
            Url::generate(
                '/product-label-gui/set-status/inactive',
                [
                    SetStatusController::PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                ]
            ),
            'Deactivate'
        );
    }

    /**
     * @param int $idProductLabel
     *
     * @return string
     */
    protected function createActivateButton($idProductLabel)
    {
        return $this->generateViewButton(
            Url::generate(
                'product-label-gui/set-status/active',
                [
                    SetStatusController::PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                ]
            ),
            'Activate',
            [
                static::BUTTON_ICON => '',
            ]
        );
    }
}
