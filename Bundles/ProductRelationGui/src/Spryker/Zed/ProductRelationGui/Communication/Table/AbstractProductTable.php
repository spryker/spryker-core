<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductRelationGui\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;

abstract class AbstractProductTable extends AbstractTable
{
    protected const COL_IS_ACTIVE_AGGREGATION = 'is_active_aggregation';

    /**
     * @param array $data
     *
     * @return string
     */
    protected function getStatusLabel(array $data): string
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
    protected function isProductActive(array $data): bool
    {
        $statusAggregation = explode(',', $data[static::COL_IS_ACTIVE_AGGREGATION]);
        foreach ($statusAggregation as $status) {
            if (filter_var($status, FILTER_VALIDATE_BOOLEAN)) {
                return true;
            }
        }

        return false;
    }
}
