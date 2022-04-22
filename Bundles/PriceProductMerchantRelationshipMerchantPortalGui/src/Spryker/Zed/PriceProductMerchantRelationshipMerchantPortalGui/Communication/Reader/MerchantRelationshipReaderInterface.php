<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationshipMerchantPortalGui\Communication\Reader;

interface MerchantRelationshipReaderInterface
{
    /**
     * @param int $idMerchantRelationship
     *
     * @return string|null
     */
    public function findMerchantRelationshipNameByIdMerchantRelationship(int $idMerchantRelationship): ?string;
}
