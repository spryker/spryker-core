<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantSearch\Persistence;

use Generated\Shared\Transfer\MerchantSearchTransfer;

interface MerchantSearchEntityManagerInterface
{
    /**
     * @param int[] $merchantIds
     *
     * @return void
     */
    public function deleteMerchantSearchByMerchantIds(array $merchantIds): void;

    /**
     * @param \Generated\Shared\Transfer\MerchantSearchTransfer $merchantSearchTransfer
     *
     * @return void
     */
    public function saveMerchantSearch(MerchantSearchTransfer $merchantSearchTransfer): void;
}
