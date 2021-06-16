<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductManagement\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ProductManagementRepositoryInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $query): ModelCriteria;
}
