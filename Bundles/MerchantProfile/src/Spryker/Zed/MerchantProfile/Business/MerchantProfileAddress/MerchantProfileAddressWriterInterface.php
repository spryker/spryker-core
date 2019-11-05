<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileAddress;

use Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer;
use Generated\Shared\Transfer\MerchantProfileAddressTransfer;

interface MerchantProfileAddressWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function create(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressTransfer $merchantProfileAddressTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressTransfer
     */
    public function update(MerchantProfileAddressTransfer $merchantProfileAddressTransfer): MerchantProfileAddressTransfer;

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer
     * @param int $idMerchantProfile
     *
     * @return \Generated\Shared\Transfer\MerchantProfileAddressCollectionTransfer
     */
    public function saveMerchantProfileAddressCollection(MerchantProfileAddressCollectionTransfer $merchantProfileAddressCollectionTransfer, int $idMerchantProfile): MerchantProfileAddressCollectionTransfer;
}
