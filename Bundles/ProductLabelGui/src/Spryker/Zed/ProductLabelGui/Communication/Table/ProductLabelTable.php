<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

use Orm\Zed\ProductLabel\Persistence\Map\SpyProductLabelTableMap;
use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\ProductLabelGui\Persistence\ProductLabelGuiQueryContainerInterface;

class ProductLabelTable extends AbstractTable
{

    const TABLE_IDENTIFIER = 'product-label-table';
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
            SpyProductLabelTableMap::COL_IS_ACTIVE => 'Status',
            static::COL_ACTIONS => 'Actions',
        ]);

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
                SpyProductLabelTableMap::COL_IS_ACTIVE => $productLabelEntity->getIsActive(),
                static::COL_ACTIONS => '',
            ];
        }

        return $tableRows;
    }

}
