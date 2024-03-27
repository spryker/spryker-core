<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\CommentMerchantRelationRequestConnector\Business\Reader;

interface MerchantRelationRequestReaderInterface
{
    /**
     * @param list<string> $merchantRelationRequestUuids
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantRelationRequestTransfer>
     */
    public function getMerchantRelationRequestTransfersIndexedByUuid(
        array $merchantRelationRequestUuids
    ): array;
}
