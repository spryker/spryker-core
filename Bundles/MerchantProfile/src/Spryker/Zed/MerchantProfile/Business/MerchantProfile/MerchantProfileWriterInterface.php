<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfile;

use Generated\Shared\Transfer\MerchantProfileTransfer;

interface MerchantProfileWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function saveMerchantProfile(MerchantProfileTransfer $merchantTransfer): MerchantProfileTransfer;
}
