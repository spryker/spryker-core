<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Api\Communication\Filterer;

use Generated\Shared\Transfer\ApiRequestTransfer;

interface RequestTransferFiltererInterface
{
    /**
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $requestTransfer
     *
     * @return \Generated\Shared\Transfer\ApiRequestTransfer
     */
    public function filter(ApiRequestTransfer $requestTransfer): ApiRequestTransfer;
}
