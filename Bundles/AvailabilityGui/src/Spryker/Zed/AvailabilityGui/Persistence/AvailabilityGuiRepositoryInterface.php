<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\AvailabilityGui\Persistence;

use Propel\Runtime\ActiveQuery\ModelCriteria;

interface AvailabilityGuiRepositoryInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function expandQuery(ModelCriteria $query): ModelCriteria;
}
