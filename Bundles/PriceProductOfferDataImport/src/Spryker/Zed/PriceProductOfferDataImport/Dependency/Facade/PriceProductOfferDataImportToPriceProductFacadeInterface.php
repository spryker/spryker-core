<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\PriceProductOfferDataImport\Dependency\Facade;

interface PriceProductOfferDataImportToPriceProductFacadeInterface
{
    /**
     * @param array $priceData
     *
     * @return string
     */
    public function generatePriceDataChecksum(array $priceData): string;
}
