<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantProfile\Business\MerchantProfileGlossary;

use Generated\Shared\Transfer\MerchantProfileTransfer;

interface MerchantProfileGlossaryWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantProfileTransfer $merchantProfileTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProfileTransfer
     */
    public function saveMerchantProfileGlossaryAttributes(MerchantProfileTransfer $merchantProfileTransfer): MerchantProfileTransfer;
}
