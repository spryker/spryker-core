<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequestGui\Communication\Reader;

use Generated\Shared\Transfer\MerchantRelationRequestTransfer;

interface MerchantRelationRequestReaderInterface
{
    /**
     * @param int $idMerchantRelationRequest
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer|null
     */
    public function findMerchantRelationRequestByIdMerchantRelationRequest(
        int $idMerchantRelationRequest
    ): ?MerchantRelationRequestTransfer;
}
