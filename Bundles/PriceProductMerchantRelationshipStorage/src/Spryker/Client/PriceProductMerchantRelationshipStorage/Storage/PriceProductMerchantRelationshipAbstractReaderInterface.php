<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Client\PriceProductMerchantRelationshipStorage\Storage;

interface PriceProductMerchantRelationshipAbstractReaderInterface
{
    /**
     * @param int $idProductAbstract
     * @param int $idCompanyCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    public function findProductAbstractPrices(int $idProductAbstract, int $idCompanyCompanyBusinessUnit): array;
}
