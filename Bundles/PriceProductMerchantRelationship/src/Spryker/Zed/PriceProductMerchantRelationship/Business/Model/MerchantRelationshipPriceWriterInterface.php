<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductMerchantRelationship\Business\Model;

use Generated\Shared\Transfer\PriceProductTransfer;

interface MerchantRelationshipPriceWriterInterface
{
    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer
     */
    public function save(PriceProductTransfer $priceProductTransfer): PriceProductTransfer;

    /**
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    public function deleteByIdBusinessUnit(int $idCompanyBusinessUnit): void;

    /**
     * @return void
     */
    public function deleteAll(): void;
}
