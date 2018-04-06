<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductMeasurementUnitStorage\Business\Model;

interface ProductMeasurementUnitStorageWriterInterface
{
    /**
     * @param int[] $productMeasurementUnitIds
     *
     * @return void
     */
    public function publish(array $productMeasurementUnitIds): void;
}
