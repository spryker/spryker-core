<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Orm\Zed\ProductList\Persistence\Map\SpyProductListProductConcreteTableMap;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;
use Spryker\Zed\PropelOrm\Business\Runtime\ActiveQuery\Criteria;

class AvailableProductConcreteTable extends AbstractProductConcreteTable
{
    /**
     * @var string
     */
    protected const DEFAULT_URL = 'available-product-concrete-table';

    protected const TABLE_IDENTIFIER = self::DEFAULT_URL;

    /**
     * @param \Spryker\Zed\Gui\Communication\Table\TableConfiguration $config
     *
     * @return \Spryker\Zed\Gui\Communication\Table\TableConfiguration
     */
    protected function configure(TableConfiguration $config): TableConfiguration
    {
        $config = parent::configure($config);

        $config->setTableAttributes([
            'data-selectable' => [
                'moveToSelector' => '#productsToBeAssigned',
                'inputSelector' => '#productListAggregate_productIdsToBeAssigned',
                'counterHolderSelector' => 'a[href="#tab-content-assignment_product"]',
                'colId' => 'spy_product.id_product',
            ],
        ]);

        return $config;
    }

    /**
     * @param \Orm\Zed\Product\Persistence\SpyProductQuery $productQuery
     *
     * @return \Orm\Zed\Product\Persistence\SpyProductQuery
     */
    protected function filterQuery(SpyProductQuery $productQuery): SpyProductQuery
    {
        if ($this->getIdProductList()) {
            $productQuery
                ->useSpyProductListProductConcreteQuery(SpyProductListProductConcreteTableMap::TABLE_NAME, Criteria::LEFT_JOIN)
                    ->filterByFkProductList(null, Criteria::ISNULL)
                ->endUse()
                ->addJoinCondition(
                    SpyProductListProductConcreteTableMap::TABLE_NAME,
                    sprintf(
                        '%s = %d',
                        SpyProductListProductConcreteTableMap::COL_FK_PRODUCT_LIST,
                        $this->getIdProductList(),
                    ),
                );
        }

        return $productQuery;
    }
}
