<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductLabelGui\Communication\Table;

class AssignedProductTable extends AbstractRelatedProductRelationTable
{

    /**
     * @var string
     */
    protected $tableIdentifier = 'assigned-product-table';

    /**
     * @var string
     */
    protected $defaultUrl = 'assigned-product-table';

    /**
     * @return \Orm\Zed\Product\Persistence\SpyProductAbstractQuery
     */
    protected function getQuery()
    {
        return $this->tableQueryBuilder->buildAssignedProductQuery($this->idProductLabel);
    }

    /**
     * @return string
     */
    protected function getCheckboxCheckedAttribute()
    {
        return 'checked="checked"';
    }

}
