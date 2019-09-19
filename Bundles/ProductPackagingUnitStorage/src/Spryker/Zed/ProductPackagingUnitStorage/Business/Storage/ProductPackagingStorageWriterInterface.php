<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Zed\ProductPackagingUnitStorage\Business\Storage;

interface ProductPackagingStorageWriterInterface
{
    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function publishProductPackagingUnit(array $productConcreteIds): void;

    /**
     * @param int[] $productConcreteIds
     *
     * @return void
     */
    public function unpublishProductPackagingUnit(array $productConcreteIds): void;
}
