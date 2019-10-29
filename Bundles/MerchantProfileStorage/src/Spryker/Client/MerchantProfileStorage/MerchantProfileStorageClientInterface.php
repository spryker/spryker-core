<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\MerchantProfileStorage;

use Generated\Shared\Transfer\MerchantProfileViewTransfer;

interface MerchantProfileStorageClientInterface
{
    /**
     * Specification:
     * - Maps raw merchant profile storage data to transfer object.
     *
     * @api
     *
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\MerchantProfileViewTransfer
     */
    public function mapMerchantProfileStorageViewData(array $data): MerchantProfileViewTransfer;

    /**
     * Specification:
     * - Finds merchant profile data by idMerchant.
     *
     * @api
     *
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantProfileViewTransfer|null
     */
    public function findMerchantProfileStorageViewData(int $idMerchant): ?MerchantProfileViewTransfer;
}
