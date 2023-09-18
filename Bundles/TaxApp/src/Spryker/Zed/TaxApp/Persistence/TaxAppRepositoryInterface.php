<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\TaxApp\Persistence;

use Generated\Shared\Transfer\TaxAppConfigCollectionTransfer;
use Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer;

interface TaxAppRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\TaxAppConfigCollectionTransfer
     */
    public function getTaxAppConfigCollection(TaxAppConfigCriteriaTransfer $taxAppConfigCriteriaTransfer): TaxAppConfigCollectionTransfer;
}
