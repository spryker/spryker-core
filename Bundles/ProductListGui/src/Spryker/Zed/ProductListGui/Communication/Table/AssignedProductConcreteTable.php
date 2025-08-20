<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductListGui\Communication\Table;

use Orm\Zed\Product\Persistence\SpyProductQuery;
use Spryker\Zed\Gui\Communication\Table\TableConfiguration;

class AssignedProductConcreteTable extends AbstractProductConcreteTable
{
    /**
     * @var string
     */
    protected const DEFAULT_URL = 'assigned-product-concrete-table';

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
                'moveToSelector' => '#productsToBeDeassigned',
                'inputSelector' => '#productListAggregate_productIdsToBeDeAssigned',
                'counterHolderSelector' => 'a[href="#tab-content-deassignment_product"]',
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
        $productQuery
            ->useSpyProductListProductConcreteQuery()
                ->filterByFkProductList($this->getIdProductList())
            ->endUse();

        return $productQuery;
    }
}
