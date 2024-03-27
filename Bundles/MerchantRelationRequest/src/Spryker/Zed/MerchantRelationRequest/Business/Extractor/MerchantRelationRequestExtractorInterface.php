<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Extractor;

use ArrayObject;

interface MerchantRelationRequestExtractorInterface
{
    /**
     * @param \ArrayObject<array-key, \Generated\Shared\Transfer\MerchantRelationRequestTransfer> $merchantRelationRequests
     *
     * @return list<int>
     */
    public function extractMerchantRelationRequestIds(ArrayObject $merchantRelationRequests): array;
}
