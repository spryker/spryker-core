<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Orm\Zed\ProductLabel\Persistence\SpyProductLabel;
use Spryker\Service\UtilText\Model\Url\Url;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Communication\Controller\DeleteController;
use Spryker\Zed\ProductLabelGui\Communication\Controller\EditController;
use Spryker\Zed\ProductLabelGui\Communication\Controller\SetStatusController;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface;

class ProductLabelTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-label-table';
    const COL_STATUS = 'status';
    const COL_ACTIONS = 'actions';

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

        $config->setHeader([
            SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL => '#',
            SpyProductLabelTableMap::COL_NAME => 'Name',
            static::COL_STATUS => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

        $config->addRawColumn(static::COL_STATUS);
        $config->addRawColumn(static::COL_ACTIONS);

        $config->setDefaultSortField(
            SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL,
            TableConfiguration::SORT_DESC
        );

        return $config;
    }

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return array
     */
    protected function prepareData(TableConfiguration $config)
    {
        $query = $this->queryContainer->queryProductLabels();
        /** @var \Orm\Zed\ProductLabel\Persistence\SpyProductLabel[] $productLabelEntities */
        $productLabelEntities = $this->runQuery($query, $config, true);

        $tableRows = [];

        foreach ($productLabelEntities as $productLabelEntity) {
            $tableRows[] = [
                SpyProductLabelTableMap::COL_ID_PRODUCT_LABEL => $productLabelEntity->getIdProductLabel(),
                SpyProductLabelTableMap::COL_NAME => $productLabelEntity->getName(),
                static::COL_STATUS => $this->createStatusMarker($productLabelEntity->getIsActive()),
                static::COL_ACTIONS => $this->createActionButtons($productLabelEntity),
            ];
        }

        return $tableRows;
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

        return sprintf(
            '<span class="label %s">%s</span>',
            $statusCssClass,
            $statusName
        );
    }

    /**
     * @return string
     */
    protected function createActionButtons(SpyProductLabel $productLabelEntity)
    {
        $idProductLabel = $productLabelEntity->getIdProductLabel();
        $actionButtons = [
            $this->createEditButton($idProductLabel),
            $this->createStatusToggleButton($idProductLabel, $productLabelEntity->getIsActive()),
            $this->CreateDeleteButton($idProductLabel),
        ];

        return implode(' ', $actionButtons);
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
                'product-label-gui/edit',
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
                    SetStatusController::PARAM_ID_PRODUCT_LABEL => $idProductLabel
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
                    SetStatusController::PARAM_ID_PRODUCT_LABEL => $idProductLabel
                ]
            ),
            'Activate'
        );
    }

    /**
     * @param int $idProductLabel
     *
     * @return string
     */
    protected function createDeleteButton($idProductLabel)
    {
        return $this->generateRemoveButton(
            Url::generate(
                '/product-label-gui/delete',
                [
                    DeleteController::PARAM_ID_PRODUCT_LABEL => $idProductLabel,
                ]
            ),
            'Delete'
        );
    }

}
