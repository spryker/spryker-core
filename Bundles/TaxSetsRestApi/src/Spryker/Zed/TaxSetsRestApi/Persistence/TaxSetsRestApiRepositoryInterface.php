<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxSetsRestApi\Persistence;

interface TaxSetsRestApiRepositoryInterface
{
    /**
     * @return \Orm\Zed\Tax\Persistence\SpyTaxSet[]
     */
    public function getTaxSetEntitiesWithoutUuid(): array;
}
