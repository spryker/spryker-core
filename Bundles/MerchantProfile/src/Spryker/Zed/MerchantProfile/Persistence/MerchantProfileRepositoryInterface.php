<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Persistence;

use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;
use Generated\Shared\Transfer\MerchantProfileTransfer;

interface MerchantProfileRepositoryInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer|null
     */
    public function findOne(MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer): ?MerchantProfileTransfer;
}
