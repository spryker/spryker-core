<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Communication\Table;

use Spryker\Zed\Gui\Communication\Table\AbstractTable;

abstract class AbstractProductTable extends AbstractTable
{
    /**
     * @param string $status
     *
     * @return string
     */
    protected function getStatusLabel($status)
    {
        if (!$status) {
            return $this->generateLabel('Inactive', 'label-danger');
        }

        return $this->generateLabel('Active', 'label-info');
    }
}
