<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Persistence;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;

interface MerchantProfileStorageRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Orm\Zed\MerchantProfileStorage\Persistence\SpyMerchantProfileStorage[]
     */
    public function getFilteredMerchantProfileStorageEntities(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): array;
}
