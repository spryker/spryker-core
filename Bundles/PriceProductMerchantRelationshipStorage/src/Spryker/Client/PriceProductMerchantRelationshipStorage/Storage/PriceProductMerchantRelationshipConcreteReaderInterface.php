<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

use Generated\Shared\Transfer\PriceProductStorageTransfer;

interface PriceProductMerchantRelationshipConcreteReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idMerchantRelationship
     *
     * @return \Generated\Shared\Transfer\PriceProductStorageTransfer|null
     */
    public function findPriceMerchantRelationshipConcrete(int $idProductAbstract, int $idMerchantRelationship): ?PriceProductStorageTransfer;
}
