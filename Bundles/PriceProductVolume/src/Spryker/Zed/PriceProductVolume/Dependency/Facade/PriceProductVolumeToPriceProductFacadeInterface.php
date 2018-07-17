<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductVolume\Dependency\Facade;

use Generated\Shared\Transfer\PriceProductCriteriaTransfer;

interface PriceProductVolumeToPriceProductFacadeInterface
{
    /**
     * @param int $idProductAbstract
     * @param \Generated\Shared\Transfer\PriceProductCriteriaTransfer|null $priceProductCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductCriteriaTransfer[]
     */
    public function findProductAbstractPricesWithoutPriceExtraction(int $idProductAbstract, ?PriceProductCriteriaTransfer $priceProductCriteriaTransfer = null): array;
}
