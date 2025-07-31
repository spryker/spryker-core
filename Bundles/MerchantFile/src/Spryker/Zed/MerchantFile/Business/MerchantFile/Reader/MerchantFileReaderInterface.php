<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace Spryker\Zed\MerchantFile\Business\MerchantFile\Reader;

use Generated\Shared\Transfer\MerchantFileCriteriaTransfer;

interface MerchantFileReaderInterface
{
    /**
     * @param \Generated\Shared\Transfer\MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer
     *
     * @return resource
     */
    public function readMerchantFileStream(MerchantFileCriteriaTransfer $merchantFileCriteriaTransfer);
}
