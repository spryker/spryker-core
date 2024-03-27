<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Filter;

use ArrayObject;
use Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer;

interface MerchantRelationRequestFilterInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $validMerchantRelationRequestTransfers
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $invalidMerchantRelationRequestTransfers
     *
     * @return \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    public function mergeMerchantRelationRequests(
        ArrayObject $validMerchantRelationRequestTransfers,
        ArrayObject $invalidMerchantRelationRequestTransfers
    ): ArrayObject;

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
     *
     * @return list<\ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>>
     */
    public function filterMerchantRelationRequestsByValidity(
        MerchantRelationRequestCollectionResponseTransfer $merchantRelationRequestCollectionResponseTransfer
    ): array;
}
