<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

interface PriceProductMerchantRelationshipConcreteReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findPriceMerchantRelationshipConcrete(int $idProductAbstract, int $idCompanyBusinessUnit): array;
}
