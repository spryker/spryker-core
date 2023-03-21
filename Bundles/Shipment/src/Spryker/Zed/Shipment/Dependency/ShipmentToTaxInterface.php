<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\Shipment\Dependency;

use Generated\Shared\Transfer\TaxSetCollectionTransfer;

interface ShipmentToTaxInterface
{
    /**
     * @return string
     */
    public function getDefaultTaxCountryIso2Code(): string;

    /**
     * @return float
     */
    public function getDefaultTaxRate(): float;

    /**
     * @return \Generated\Shared\Transfer\TaxSetCollectionTransfer
     */
    public function getTaxSets(): TaxSetCollectionTransfer;
}
