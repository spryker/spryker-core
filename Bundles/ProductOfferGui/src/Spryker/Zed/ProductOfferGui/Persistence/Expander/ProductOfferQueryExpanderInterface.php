<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductOfferGui\Persistence\Expander;

use Generated\Shared\Transfer\QueryCriteriaTransfer;
use Propel\Runtime\ActiveQuery\ModelCriteria;

interface ProductOfferQueryExpanderInterface
{
    /**
     * @param \Propel\Runtime\ActiveQuery\ModelCriteria $query
     * @param \Generated\Shared\Transfer\QueryCriteriaTransfer $queryCriteriaTransfer
     *
     * @return \Propel\Runtime\ActiveQuery\ModelCriteria
     */
    public function mapQueryCriteriaTransferToModelCriteria(ModelCriteria $query, QueryCriteriaTransfer $queryCriteriaTransfer): ModelCriteria;
}
