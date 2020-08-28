<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductMeasurementUnitsRestApi\Processor\Translator;

interface ProductMeasurementUnitNameTranslatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[] $productMeasurementUnitTransfers
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer[]
     */
    public function getProductMeasurementUnitTransfersWithTranslatedNames(
        array $productMeasurementUnitTransfers,
        string $localeName
    ): array;
}
