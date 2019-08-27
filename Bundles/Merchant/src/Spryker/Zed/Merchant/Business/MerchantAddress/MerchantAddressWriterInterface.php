<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Merchant\Business\MerchantAddress;

use Generated\Shared\Transfer\MerchantAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantAddressTransfer;

interface MerchantAddressWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function create(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressTransfer $merchantAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantAddressTransfer
     */
    public function update(MerchantAddressTransfer $merchantAddressTransfer): MerchantAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer
     * @param int $idMerchant
     *
     * @return \Generated\Shared\Transfer\MerchantAddressCollectionTransfer
     */
    public function handleMerchantAddressCollectionSave(MerchantAddressCollectionTransfer $merchantAddressCollectionTransfer, int $idMerchant): MerchantAddressCollectionTransfer;
}
