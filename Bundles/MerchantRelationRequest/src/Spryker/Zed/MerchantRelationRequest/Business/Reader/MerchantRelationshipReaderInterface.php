<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\MerchantRelationRequest\Business\Reader;

interface MerchantRelationshipReaderInterface
{
    /**
     * @param list<string> $merchantRelationRequestUuids
     *
     * @return array<string, list<\Generated\Shared\Transfer\MerchantRelationshipTransfer>>
     */
    public function getMerchantRelationshipsGroupedByMerchantRelationshipRequestUuid(array $merchantRelationRequestUuids): array;
}
