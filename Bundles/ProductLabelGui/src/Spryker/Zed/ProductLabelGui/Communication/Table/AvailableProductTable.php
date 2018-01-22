<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

class AvailableProductTable extends AbstractRelatedProductRelationTable
{
    /**
     * @var string
     */
    protected $tableIdentifier = 'available-product-table';

    /**
     * @var string
     */
    protected $defaultUrl = 'available-product-table';

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getQuery()
    {
        return $this->tableQueryBuilder->buildAvailableProductQuery($this->idProductLabel);
    }

    /**
     * @return string
     */
    protected function getCheckboxCheckedAttribute()
    {
        return '';
    }
}
