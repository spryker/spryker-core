<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantCommission\Business\Importer;

use Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer;
use Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer;

interface MerchantCommissionImporterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantCommissionCollectionResponseTransfer
     */
    public function importMerchantCommissionCollection(
        MerchantCommissionCollectionRequestTransfer $merchantCommissionCollectionRequestTransfer
    ): MerchantCommissionCollectionResponseTransfer;
}
