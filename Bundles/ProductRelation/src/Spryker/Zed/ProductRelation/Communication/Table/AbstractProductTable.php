<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelation\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;
use Spryker\Zed\ProductRelation\Persistence\ProductRelationQueryContainer;

abstract class AbstractProductTable extends AbstractTable
{
    /**
     * @param array $data
     *
     * @return string
     */
    protected function getStatusLabel(array $data)
    {
        if (!$this->isProductActive($data)) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }

    /**
     * @param array $data
     *
     * @return bool
     */
    protected function isProductActive(array $data)
    {
        $statusAggregation = explode(',', $data[ProductRelationQueryContainer::COL_IS_ACTIVE_AGGREGATION]);
        foreach ($statusAggregation as $status) {
            if (filter_var($status, FILTER_VALIDATE_BOOLEAN)) {
                return true;
            }
        }

        return false;
    }
}
