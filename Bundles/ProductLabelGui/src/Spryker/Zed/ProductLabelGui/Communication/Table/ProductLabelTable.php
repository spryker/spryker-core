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
use Orm\Zed\Store\Persistence\Map\SpyStoreTableMap;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Communication\Controller\DeleteController;
use Spryker\Zed\ProductLabelGui\Communication\Controller\EditController;
use Spryker\Zed\ProductLabelGui\Communication\Controller\ViewController;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface;

class ProductLabelTable extends AbstractTable
{
    public const COL_ID_PRODUCT_LABEL = SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL;
    public const COL_NAME = SpyProductLabelTableMap::COL_NAME;
    public const COL_IS_EXCLUSIVE = SpyProductLabelTableMap::COL_IS_EXCLUSIVE;
    public const COL_IS_DYNAMIC = SpyProductLabelTableMap::COL_IS_DYNAMIC;
    public const COL_STATUS = SpyProductLabelTableMap::COL_IS_ACTIVE;
    public const COL_STORES = SpyStoreTableMap::COL_NAME;
    public const COL_PRIORITY = SpyProductLabelTableMap::COL_POSITION;
    /**
     * @var string
     */
    public const COL_ABSTRACT_PRODUCT_RELATION_COUNT = 'abstract_product_relation_count';
    /**
     * @var string
     */
    public const COL_ACTIONS = 'actions';

    /**
     * @var string
     */
    public const TABLE_IDENTIFIER = 'product-label-table';

    /**
     * @uses \Spryker\Zed\ProductLabelGui\Communication\Controller\IndexController::indexAction()
     * @var string
     */
    protected const URL_PRODUCT_LABEL_LIST = '/product-label-gui';

    /**
     * @uses \Spryker\Zed\ProductLabelGui\Communication\Controller\ViewController::indexAction()
     * @var string
     */
    protected const URL_PRODUCT_LABEL_VIEW = '/product-label-gui/view';

    /**
     * @uses \Spryker\Zed\ProductLabelGui\Communication\Controller\EditController::indexAction()
     * @var string
     */
    protected const URL_PRODUCT_LABEL_EDIT = '/product-label-gui/edit';

    /**
     * @uses \Spryker\Zed\ProductLabelGui\Communication\Controller\DeleteController::indexAction()
     * @var string
     */
    protected const URL_PRODUCT_LABEL_DELETE = '/product-label-gui/delete';

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
            static::COL_ID_PRODUCT_LABEL => 'ID',
            static::COL_NAME => 'Name',
            static::COL_IS_EXCLUSIVE => 'Is Exclusive',
            static::COL_IS_DYNAMIC => 'Is Dynamic',
            static::COL_STATUS => 'Status',
            static::COL_STORES => 'Stores',
            static::COL_PRIORITY => 'Priority',
            static::COL_ABSTRACT_PRODUCT_RELATION_COUNT => 'Number of Products',
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
        $config->setRawColumns([
            static::COL_STATUS,
            static::COL_STORES,
            static::COL_ACTIONS,
            static::COL_IS_DYNAMIC,
            static::COL_IS_EXCLUSIVE,
        ]);
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
            static::COL_PRIORITY,
            static::COL_NAME,
            static::COL_IS_EXCLUSIVE,
            static::COL_IS_DYNAMIC,
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
        /** @var array<\Orm\Zed\ProductLabel\Persistence\SpyProductLabel> $productLabelEntities */
        $productLabelEntities = $this->runQuery($query, $config, true);

        $tableRows = [];

        foreach ($productLabelEntities as $productLabelEntity) {
            $tableRows[] = [
                static::COL_ID_PRODUCT_LABEL => $productLabelEntity->getIdProductLabel(),
                static::COL_NAME => $productLabelEntity->getName(),
                static::COL_IS_EXCLUSIVE => $this->getIsExclusiveLabel($productLabelEntity),
                static::COL_IS_DYNAMIC => $this->getIsDynamicLabel($productLabelEntity),
                static::COL_STATUS => $this->createStatusMarker($productLabelEntity->getIsActive()),
                static::COL_STORES => $this->getStoreNames($productLabelEntity),
                static::COL_PRIORITY => $productLabelEntity->getPosition(),
                static::COL_ABSTRACT_PRODUCT_RELATION_COUNT => $productLabelEntity->getVirtualColumn(static::COL_ABSTRACT_PRODUCT_RELATION_COUNT),
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
        return $this->generateLabel(
            $productLabelEntity->getIsExclusive() ? 'Yes' : 'No',
            null
        );
    }

    /**
     * @param \Orm\Zed\ProductLabel\Persistence\SpyProductLabel $productLabelEntity
     *
     * @return string
     */
    protected function getIsDynamicLabel(SpyProductLabel $productLabelEntity)
    {
        return $this->generateLabel(
            $productLabelEntity->getIsDynamic() ? 'Yes' : 'No',
            null
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
    protected function getStoreNames(SpyProductLabel $productLabelEntity): string
    {
        $storeNames = [];
        foreach ($productLabelEntity->getProductLabelStores() as $productLabelStore) {
            $storeNames[] = $this->generateLabel($productLabelStore->getStore()->getName(), 'label-info');
        }

        return implode(' ', $storeNames);
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
            $this->createDeleteButton($idProductLabel),
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
                static::URL_PRODUCT_LABEL_VIEW,
                [
                    ViewController::PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                ]
            ),
            'View',
            [
                'icon' => 'fa-eye',
            ]
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
                static::URL_PRODUCT_LABEL_EDIT,
                [
                    EditController::PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                ]
            ),
            'Edit'
        );
    }

    /**
     * @param int $idProductLabel
     *
     * @return string
     */
    protected function createDeleteButton(int $idProductLabel): string
    {
        return $this->generateRemoveButton(
            Url::generate(
                static::URL_PRODUCT_LABEL_DELETE,
                [
                    DeleteController::URL_PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                    DeleteController::URL_PARAM_REDIRECT_URL => static::URL_PRODUCT_LABEL_LIST,
                ]
            ),
            'Delete'
        );
    }
}
