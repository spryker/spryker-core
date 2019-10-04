<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfileStorage\Dependency\Facade;

use Generated\Shared\Transfer\MerchantProfileCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer;

interface MerchantProfileStorageToMerchantProfileFacadeInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileCriteriaFilterTransfer|null $merchantProfileCriteriaFilterTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileCollectionTransfer
     */
    public function find(?MerchantProfileCriteriaFilterTransfer $merchantProfileCriteriaFilterTransfer = null): MerchantProfileCollectionTransfer;
}
