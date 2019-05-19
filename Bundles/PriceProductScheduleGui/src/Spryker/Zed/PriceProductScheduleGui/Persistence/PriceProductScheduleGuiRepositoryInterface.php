<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductScheduleGui\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface PriceProductScheduleGuiRepositoryInterface
{
    /**
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function getPriceProductScheduleQuery(): ModelCriteria;
}
