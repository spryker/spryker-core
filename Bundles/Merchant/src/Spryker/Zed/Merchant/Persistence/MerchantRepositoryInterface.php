<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Persistence;

use Generated\Shared\Transfer\MerchantCollectionTransfer;
use Generated\Shared\Transfer\MerchantCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantTransfer;

interface MerchantRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCollectionTransfer
     */
    public function find(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): MerchantCollectionTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantTransfer|null
     */
    public function findOne(MerchantCriteriaFilterTransfer $merchantCriteriaFilterTransfer): ?MerchantTransfer;
}
